<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_verse_model extends MY_Model
{
	protected $table = 'daily_verses';
	protected $order_by = 'publish_date DESC, id DESC';

	public function get_current_verse()
	{
		$today = date('Y-m-d');
		$verse = $this->db
			->where('is_published', 1)
			->where('publish_date <=', $today)
			->order_by('publish_date', 'DESC')
			->limit(1)
			->get($this->table)
			->row();

		return $verse ? $verse : $this->db->where('is_published', 1)->order_by('publish_date', 'DESC')->limit(1)->get($this->table)->row();
	}
}