<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_update_model extends MY_Model
{
	protected $table = 'news_updates';
	protected $order_by = 'publish_date DESC, id DESC';

	public function get_published($search = '', $limit = NULL)
	{
		$this->db->from($this->table);
		$this->db->where('status', 'published');

		if ($search !== '')
		{
			$this->db->group_start()
				->like('title', $search)
				->or_like('summary', $search)
				->or_like('content', $search)
				->or_like('category', $search)
			->group_end();
		}

		$this->db->order_by('publish_date', 'DESC');

		if ($limit !== NULL)
		{
			$this->db->limit($limit);
		}

		return $this->db->get()->result();
	}

	public function get_featured($limit = 3)
	{
		return $this->db->where(array('status' => 'published', 'is_featured' => 1))->order_by('publish_date', 'DESC')->limit($limit)->get($this->table)->result();
	}

	public function get_by_slug($slug)
	{
		return $this->get_by(array('slug' => $slug), TRUE);
	}
}