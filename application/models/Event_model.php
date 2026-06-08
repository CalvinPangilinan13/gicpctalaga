<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_model extends MY_Model
{
	protected $table = 'events';
	protected $order_by = 'start_datetime ASC, id ASC';

	public function get_upcoming($limit = 6)
	{
		return $this->db
			->where('status', 'published')
			->where('start_datetime >=', date('Y-m-d H:i:s'))
			->order_by('start_datetime', 'ASC')
			->limit($limit)
			->get($this->table)
			->result();
	}

	public function get_published($search = '')
	{
		$this->db->from($this->table);
		$this->db->where('status', 'published');

		if ($search !== '')
		{
			$this->db->group_start()
				->like('title', $search)
				->or_like('summary', $search)
				->or_like('description', $search)
				->or_like('location', $search)
			->group_end();
		}

		$this->db->order_by('start_datetime', 'ASC');

		return $this->db->get()->result();
	}

	public function get_by_slug($slug)
	{
		return $this->get_by(array('slug' => $slug), TRUE);
	}
}