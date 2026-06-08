<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property User_model $user_model
 * @property Activity_log_model $activity_log_model
 * @property Setting_model $setting_model
 */
class Auth extends CI_Controller
{
	/** @var User_model */
	public $user_model;
	/** @var Activity_log_model */
	public $activity_log_model;
	/** @var Setting_model */
	public $setting_model;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('user_model', 'activity_log_model', 'setting_model'));
	}

	public function login()
	{
		if ($this->session->userdata('logged_in'))
		{
			redirect('admin');
		}

		if (strtoupper($this->input->method()) === 'POST')
		{
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');

			if ($this->form_validation->run())
			{
				$user = $this->user_model->authenticate($this->input->post('email', TRUE), $this->input->post('password', FALSE));

				if ($user)
				{
					$this->session->set_userdata(array(
						'logged_in' => TRUE,
						'user_id' => $user->id,
						'user_name' => trim($user->first_name.' '.$user->last_name),
						'user_role' => $user->role_slug,
					));

					$this->activity_log_model->log(array(
						'user_id' => $user->id,
						'action' => 'login',
						'module' => 'auth',
						'description' => 'User signed into the CMS.',
						'ip_address' => $this->input->ip_address(),
						'created_at' => date('Y-m-d H:i:s'),
					));

					redirect('admin');
				}

				$this->session->set_flashdata('auth_error', 'Invalid email address or password.');
			}
		}

		$this->load->view('admin/auth/login', array(
			'settings' => $this->setting_model->get_keyed_settings(),
		));
	}

	public function forgot_password()
	{
		if (strtoupper($this->input->method()) === 'POST')
		{
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

			if ($this->form_validation->run())
			{
				$user = $this->user_model->create_reset_token($this->input->post('email', TRUE));

				if ($user)
				{
					$this->session->set_flashdata('auth_success', 'Password reset token generated. Use this link: '.site_url('admin/reset-password/'.$user->reset_token));
					redirect('admin/forgot-password');
				}

				$this->session->set_flashdata('auth_error', 'No user account was found for that email address.');
			}
		}

		$this->load->view('admin/auth/forgot_password');
	}

	public function reset_password($token = NULL)
	{
		$user = $token ? $this->user_model->get_by_reset_token($token) : NULL;

		if (!$user)
		{
			show_404();
		}

		if (strtoupper($this->input->method()) === 'POST')
		{
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
			$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|required|matches[password]');

			if ($this->form_validation->run())
			{
				$this->user_model->reset_password($user->id, $this->input->post('password', FALSE));
				$this->session->set_flashdata('auth_success', 'Your password has been reset. You may now sign in.');
				redirect('admin/login');
			}
		}

		$this->load->view('admin/auth/reset_password', array('token' => $token));
	}

	public function change_password()
	{
		if (!$this->session->userdata('logged_in'))
		{
			redirect('admin/login');
		}

		if (strtoupper($this->input->method()) === 'POST')
		{
			$this->form_validation->set_rules('password', 'New Password', 'trim|required|min_length[8]');
			$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|required|matches[password]');

			if ($this->form_validation->run())
			{
				$this->user_model->reset_password((int) $this->session->userdata('user_id'), $this->input->post('password', FALSE));
				$this->session->set_flashdata('admin_success', 'Your password has been updated.');
				redirect('admin');
			}
		}

		$this->load->view('admin/auth/change_password');
	}

	public function logout()
	{
		$user_id = (int) $this->session->userdata('user_id');

		if ($user_id > 0)
		{
			$this->activity_log_model->log(array(
				'user_id' => $user_id,
				'action' => 'logout',
				'module' => 'auth',
				'description' => 'User signed out of the CMS.',
				'ip_address' => $this->input->ip_address(),
				'created_at' => date('Y-m-d H:i:s'),
			));
		}

		$this->session->sess_destroy();
		redirect('admin/login');
	}
}