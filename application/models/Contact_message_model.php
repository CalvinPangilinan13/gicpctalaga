<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_message_model extends MY_Model
{
	protected $table = 'contact_messages';
	protected $order_by = 'created_at DESC';
}