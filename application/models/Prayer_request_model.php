<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prayer_request_model extends MY_Model
{
	protected $table = 'prayer_requests';
	protected $order_by = 'created_at DESC';
}