<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_log_model extends MY_Model
{
	protected $table = 'activity_logs';
	protected $order_by = 'created_at DESC';

	public function log($data)
	{
		return $this->insert($data);
	}

	public function get_recent($limit = 10)
	{
		$this->db->select('activity_logs.*, CONCAT(users.first_name, " ", users.last_name) AS user_name');
		$this->db->from($this->table);
		$this->db->join('users', 'users.id = activity_logs.user_id', 'left');
		$this->db->order_by('activity_logs.created_at', 'DESC');
		$this->db->limit($limit);

		return $this->db->get()->result();
	}
}