<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends MY_Model
{
	protected $table = 'roles';
	protected $order_by = 'name ASC';

	public function get_all_with_permission_summary()
	{
		$items = $this->get_all();

		foreach ($items as $item)
		{
			$permissions = normalize_role_permissions(isset($item->permissions) ? $item->permissions : NULL, TRUE);
			$item->permission_count = $permissions === NULL ? count(admin_permission_options()) : count($permissions);
		}

		return $items;
	}
}