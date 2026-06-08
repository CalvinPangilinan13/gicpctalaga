<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sermon_file_model extends MY_Model
{
	protected $table = 'sermon_files';
	protected $order_by = 'id ASC';

	public function get_by_sermon($sermon_id)
	{
		return $this->get_by(array('sermon_id' => $sermon_id));
	}
}