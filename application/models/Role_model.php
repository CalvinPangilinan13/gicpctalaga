<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends MY_Model
{
	protected $table = 'roles';
	protected $order_by = 'name ASC';
}