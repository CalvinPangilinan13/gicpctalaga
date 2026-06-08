<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends MY_Model
{
	protected $table = 'menu_items';
	protected $order_by = 'sort_order ASC, id ASC';

	public function get_visible_menu()
	{
		return $this->get_by(array('is_visible' => 1));
	}
}