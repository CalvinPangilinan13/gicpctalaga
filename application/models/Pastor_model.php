<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pastor_model extends MY_Model
{
	protected $table = 'pastors';
	protected $order_by = 'display_order ASC, name ASC';

	public function get_featured($limit = 4)
	{
		return $this->db->where('is_featured', 1)->order_by('display_order', 'ASC')->limit($limit)->get($this->table)->result();
	}
}