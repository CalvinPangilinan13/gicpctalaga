<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_model extends MY_Model
{
	protected $table = 'galleries';
	protected $order_by = 'album_date DESC, id DESC';

	public function get_published($limit = NULL)
	{
		$this->db->from($this->table);
		$this->db->where('status', 'published');
		$this->db->order_by('album_date', 'DESC');

		if ($limit !== NULL)
		{
			$this->db->limit($limit);
		}

		return $this->db->get()->result();
	}

	public function get_featured($limit = 3)
	{
		return $this->db->where(array('status' => 'published', 'is_featured' => 1))->order_by('album_date', 'DESC')->limit($limit)->get($this->table)->result();
	}

	public function get_by_slug($slug)
	{
		return $this->get_by(array('slug' => $slug), TRUE);
	}
}