<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model
{
	protected $table = 'users';
	protected $order_by = 'created_at DESC';

	public function get_by_email($email)
	{
		return $this->get_by(array('email' => $email), TRUE);
	}

	public function get_with_role($id)
	{
		$this->db->select('users.*, roles.name AS role_name, roles.slug AS role_slug, roles.permissions AS role_permissions');
		$this->db->from($this->table);
		$this->db->join('roles', 'roles.id = users.role_id');
		$this->db->where('users.id', $id);

		return $this->db->get()->row();
	}

	public function get_all_with_roles()
	{
		$this->db->select('users.*, roles.name AS role_name');
		$this->db->from($this->table);
		$this->db->join('roles', 'roles.id = users.role_id');
		$this->db->order_by('users.created_at', 'DESC');

		return $this->db->get()->result();
	}

	public function authenticate($email, $password)
	{
		$user = $this->get_by_email($email);

		if (!$user || (int) $user->is_active !== 1)
		{
			return NULL;
		}

		$info = password_get_info($user->password_hash);
		$valid = !empty($info['algo']) ? password_verify($password, $user->password_hash) : hash_equals($user->password_hash, $password);

		if (!$valid)
		{
			return NULL;
		}

		if (empty($info['algo']))
		{
			$this->update($user->id, array('password_hash' => password_hash($password, PASSWORD_BCRYPT)));
		}

		$this->update($user->id, array(
			'last_login_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		));

		return $this->get_with_role($user->id);
	}

	public function save_user($data, $id = NULL)
	{
		if (!empty($data['password']))
		{
			$data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
		}

		unset($data['password']);

		if ($id === NULL)
		{
			return $this->insert($data);
		}

		$this->update($id, $data);

		return $id;
	}

	public function create_reset_token($email)
	{
		$user = $this->get_by(array('email' => $email), TRUE);

		if (!$user)
		{
			return FALSE;
		}

		$token = bin2hex(random_bytes(20));
		$this->update($user->id, array(
			'reset_token' => $token,
			'reset_expires_at' => date('Y-m-d H:i:s', strtotime('+2 hours')),
			'updated_at' => date('Y-m-d H:i:s'),
		));

		$user->reset_token = $token;
		$user->reset_expires_at = date('Y-m-d H:i:s', strtotime('+2 hours'));

		return $user;
	}

	public function get_by_reset_token($token)
	{
		return $this->db
			->where('reset_token', $token)
			->where('reset_expires_at >=', date('Y-m-d H:i:s'))
			->get($this->table)
			->row();
	}

	public function reset_password($user_id, $password)
	{
		return $this->update($user_id, array(
			'password_hash' => password_hash($password, PASSWORD_BCRYPT),
			'reset_token' => NULL,
			'reset_expires_at' => NULL,
			'updated_at' => date('Y-m-d H:i:s'),
		));
	}
}