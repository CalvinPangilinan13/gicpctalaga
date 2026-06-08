<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_schedule_model extends MY_Model
{
	protected $table = 'service_schedules';
	protected $order_by = 'display_order ASC, id ASC';

	public function get_public_schedules()
	{
		return $this->get_by(array('is_visible' => 1));
	}
}