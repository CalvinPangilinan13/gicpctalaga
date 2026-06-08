<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_registration_model extends MY_Model
{
	protected $table = 'event_registrations';
	protected $order_by = 'created_at DESC';

	public function count_for_event($event_id)
	{
		return $this->count_all(array('event_id' => $event_id));
	}
}