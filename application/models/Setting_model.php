<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends MY_Model
{
	protected $table = 'settings';
	protected $order_by = 'setting_group ASC, label ASC';

	public function get_keyed_settings()
	{
		$settings = array();

		foreach ($this->get_all() as $setting)
		{
			$settings[$setting->setting_key] = $setting->setting_value;
		}

		return $settings;
	}

	public function get_grouped_settings()
	{
		$grouped = array();

		foreach ($this->get_all() as $setting)
		{
			$grouped[$setting->setting_group][] = $setting;
		}

		return $grouped;
	}

	public function increment_setting($key)
	{
		$existing = $this->get_by(array('setting_key' => $key), TRUE);
		$timestamp = date('Y-m-d H:i:s');

		if ($existing)
		{
			return $this->update($existing->id, array(
				'setting_value' => (string) (((int) $existing->setting_value) + 1),
				'updated_at' => $timestamp,
			));
		}

		return $this->insert(array(
			'setting_key' => $key,
			'setting_value' => '1',
			'setting_group' => 'analytics',
			'label' => ucwords(str_replace('_', ' ', $key)),
			'created_at' => $timestamp,
			'updated_at' => $timestamp,
		));
	}

	public function set_items($items, $group = 'general')
	{
		$timestamp = date('Y-m-d H:i:s');

		foreach ($items as $key => $value)
		{
			$existing = $this->get_by(array('setting_key' => $key), TRUE);
			$payload = array(
				'setting_key' => $key,
				'setting_value' => $value,
				'setting_group' => $group,
				'label' => ucwords(str_replace('_', ' ', $key)),
				'updated_at' => $timestamp,
			);

			if ($existing)
			{
				$this->update($existing->id, $payload);
				continue;
			}

			$payload['created_at'] = $timestamp;
			$this->insert($payload);
		}
	}
}