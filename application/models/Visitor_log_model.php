<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitor_log_model extends MY_Model
{
	protected $table = 'visitor_logs';
	protected $order_by = 'created_at DESC';

	public function log_visit($ip_address, $user_agent, $visited_on)
	{
		if ($this->has_visit($ip_address, $user_agent, $visited_on))
		{
			return FALSE;
		}

		return $this->insert(array(
			'ip_address' => $ip_address,
			'user_agent' => $user_agent,
			'visited_on' => $visited_on,
			'created_at' => date('Y-m-d H:i:s'),
		));
	}

	public function count_logged_visits()
	{
		$result = $this->db
			->select('COUNT(DISTINCT CONCAT(ip_address, "|", COALESCE(user_agent, ""), "|", visited_on)) AS total_visits', FALSE)
			->get($this->table)
			->row();

		return isset($result->total_visits) ? (int) $result->total_visits : 0;
	}

	protected function has_visit($ip_address, $user_agent, $visited_on)
	{
		return $this->db
			->where('ip_address', $ip_address)
			->where('user_agent', $user_agent)
			->where('visited_on', $visited_on)
			->count_all_results($this->table) > 0;
	}
}