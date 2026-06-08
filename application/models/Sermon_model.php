<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sermon_model extends MY_Model
{
	protected $table = 'sermons';
	protected $order_by = 'sermon_date DESC, id DESC';

	public function get_published($search = '', $limit = NULL)
	{
		$this->db->from($this->table);
		$this->db->where('status', 'published');

		if ($search !== '')
		{
			$this->db->group_start()
				->like('title', $search)
				->or_like('speaker', $search)
				->or_like('excerpt', $search)
				->or_like('content', $search)
			->group_end();
		}

		$this->db->order_by('sermon_date', 'DESC');

		if ($limit !== NULL)
		{
			$this->db->limit($limit);
		}

		return $this->db->get()->result();
	}

	public function get_by_slug($slug)
	{
		return $this->get_by(array('slug' => $slug), TRUE);
	}
}