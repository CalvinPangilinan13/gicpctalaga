<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Church_profile_model $church_profile_model
 * @property Daily_verse_model $daily_verse_model
 * @property Announcement_model $announcement_model
 * @property Event_model $event_model
 * @property News_update_model $news_update_model
 * @property Ministry_model $ministry_model
 * @property Pastor_model $pastor_model
 * @property Sermon_model $sermon_model
 * @property Gallery_model $gallery_model
 * @property Service_schedule_model $service_schedule_model
 * @property Visitor_log_model $visitor_log_model
 * @property Setting_model $setting_model
 * @property Menu_model $menu_model
 * @property Activity_log_model $activity_log_model
 */
class Site_Controller extends CI_Controller
{
	/** @var Church_profile_model */
	public $church_profile_model;
	/** @var Daily_verse_model */
	public $daily_verse_model;
	/** @var Announcement_model */
	public $announcement_model;
	/** @var Event_model */
	public $event_model;
	/** @var News_update_model */
	public $news_update_model;
	/** @var Ministry_model */
	public $ministry_model;
	/** @var Pastor_model */
	public $pastor_model;
	/** @var Sermon_model */
	public $sermon_model;
	/** @var Gallery_model */
	public $gallery_model;
	/** @var Service_schedule_model */
	public $service_schedule_model;
	/** @var Visitor_log_model */
	public $visitor_log_model;
	/** @var Setting_model */
	public $setting_model;
	/** @var Menu_model */
	public $menu_model;
	/** @var Activity_log_model */
	public $activity_log_model;
	protected $site_data = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'church_profile_model',
			'daily_verse_model',
			'announcement_model',
			'event_model',
			'news_update_model',
			'ministry_model',
			'pastor_model',
			'sermon_model',
			'gallery_model',
			'visitor_log_model',
			'service_schedule_model'
		));

		$this->site_data = array(
			'settings' => $this->setting_model->get_keyed_settings(),
			'church_profile' => $this->church_profile_model->get_primary_profile(),
			'menu_items' => $this->menu_model->get_visible_menu(),
			'daily_verse' => $this->daily_verse_model->get_current_verse(),
			'service_schedules' => $this->service_schedule_model->get_public_schedules(),
		);

		$this->track_visitor();
	}

	protected function render($view, $data = array())
	{
		$data = array_merge($this->site_data, $data);
		$this->load->view('site/layout/header', $data);
		$this->load->view($view, $data);
		$this->load->view('site/layout/footer', $data);
	}

	protected function track_visitor()
	{
		$visited_on = date('Y-m-d');

		if ($this->session->userdata('visitor_logged_on') === $visited_on)
		{
			return;
		}

		$this->visitor_log_model->log_visit(
			$this->input->ip_address(),
			substr((string) $this->input->user_agent(), 0, 255),
			$visited_on
		);

		$this->session->set_userdata('visitor_logged_on', $visited_on);
	}
}

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property User_model $user_model
 * @property Role_model $role_model
 * @property Church_profile_model $church_profile_model
 * @property Setting_model $setting_model
 * @property Activity_log_model $activity_log_model
 */
class Admin_Controller extends CI_Controller
{
	/** @var User_model */
	public $user_model;
	/** @var Role_model */
	public $role_model;
	/** @var Church_profile_model */
	public $church_profile_model;
	/** @var Setting_model */
	public $setting_model;
	/** @var Activity_log_model */
	public $activity_log_model;
	protected $admin_user;
	protected $admin_permissions = NULL;
	protected $admin_data = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('user_model', 'role_model', 'church_profile_model'));
		$this->ensure_authenticated();

		$this->admin_user = $this->user_model->get_with_role((int) $this->session->userdata('user_id'));
		$this->admin_permissions = normalize_role_permissions($this->admin_user && isset($this->admin_user->role_permissions) ? $this->admin_user->role_permissions : NULL);
		$navigation = admin_navigation();
		$filtered_navigation = array();

		foreach ($navigation as $item)
		{
			$permission = isset($item['permission']) ? $item['permission'] : NULL;

			if ($permission === NULL || $this->has_admin_access($permission))
			{
				$filtered_navigation[] = $item;
			}
		}

		$this->admin_data = array(
			'admin_user' => $this->admin_user,
			'admin_menu' => $filtered_navigation,
			'church_profile' => $this->church_profile_model->get_primary_profile(),
			'settings' => $this->setting_model->get_keyed_settings(),
		);
	}

	protected function ensure_authenticated()
	{
		if (!$this->session->userdata('logged_in'))
		{
			redirect('admin/login');
		}
	}

	protected function render($view, $data = array())
	{
		$data = array_merge($this->admin_data, $data);
		$this->load->view('admin/layout/header', $data);
		$this->load->view('admin/layout/sidebar', $data);
		$this->load->view($view, $data);
		$this->load->view('admin/layout/footer', $data);
	}

	protected function has_admin_access($permission)
	{
		return role_has_permission($this->admin_permissions, $permission);
	}

	protected function authorize_admin_access($permission)
	{
		if ($this->has_admin_access($permission))
		{
			return;
		}

		show_error('You do not have permission to access this module.', 403, 'Access Denied');
	}

	protected function log_activity($action, $module, $description)
	{
		$this->activity_log_model->log(array(
			'user_id' => (int) $this->session->userdata('user_id'),
			'action' => $action,
			'module' => $module,
			'description' => $description,
			'ip_address' => $this->input->ip_address(),
			'created_at' => date('Y-m-d H:i:s'),
		));
	}

	protected function upload_file($field_name, $target_path, $allowed_types, $max_size = 4096)
	{
		if (empty($_FILES[$field_name]['name']))
		{
			return NULL;
		}

		if (!is_dir(FCPATH.$target_path))
		{
			mkdir(FCPATH.$target_path, 0755, TRUE);
		}

		$config = array(
			'upload_path' => FCPATH.$target_path,
			'allowed_types' => $allowed_types,
			'max_size' => $max_size,
			'encrypt_name' => TRUE,
		);

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload($field_name))
		{
			return array('error' => $this->upload->display_errors('', ''));
		}

		$data = $this->upload->data();

		return array(
			'path' => trim($target_path, '/').'/'.$data['file_name'],
			'original_name' => $data['client_name'],
		);
	}

	protected function upload_multiple_files($field_name, $target_path, $allowed_types, $max_size = 4096)
	{
		$uploaded = array();

		if (empty($_FILES[$field_name]['name']) || !is_array($_FILES[$field_name]['name']))
		{
			return $uploaded;
		}

		if (!is_dir(FCPATH.$target_path))
		{
			mkdir(FCPATH.$target_path, 0755, TRUE);
		}

		$files = $_FILES[$field_name];
		$count = count($files['name']);

		for ($index = 0; $index < $count; $index++)
		{
			if (empty($files['name'][$index]))
			{
				continue;
			}

			$_FILES['__multi_upload'] = array(
				'name' => $files['name'][$index],
				'type' => $files['type'][$index],
				'tmp_name' => $files['tmp_name'][$index],
				'error' => $files['error'][$index],
				'size' => $files['size'][$index],
			);

			$config = array(
				'upload_path' => FCPATH.$target_path,
				'allowed_types' => $allowed_types,
				'max_size' => $max_size,
				'encrypt_name' => TRUE,
			);

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('__multi_upload'))
			{
				return array('error' => $this->upload->display_errors('', ''));
			}

			$data = $this->upload->data();
			$uploaded[] = array(
				'path' => trim($target_path, '/').'/'.$data['file_name'],
				'original_name' => $data['client_name'],
			);
		}

		unset($_FILES['__multi_upload']);

		return $uploaded;
	}
}