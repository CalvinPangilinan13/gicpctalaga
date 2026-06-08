<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Church_profile_model extends MY_Model
{
	protected $table = 'church_profile';
	protected $order_by = 'id ASC';

	public function get_primary_profile()
	{
		$profile = $this->db->limit(1)->get($this->table)->row();

		if ($profile)
		{
			return $profile;
		}

		return (object) array(
			'church_name' => 'Grace in Christ Presbyterian Church - Talaga',
			'short_name' => 'GICP Talaga',
			'tagline' => '',
			'logo' => 'assets/images/logo.svg',
			'hero_image' => 'assets/images/hero-pattern.svg',
			'welcome_message' => '',
			'brief_intro' => '',
			'history' => '',
			'mission' => '',
			'vision' => '',
			'core_values' => '',
			'statement_of_faith' => '',
			'address' => '',
			'email' => '',
			'mobile_number' => '',
			'landline' => '',
			'google_map_embed' => '',
			'facebook_url' => '',
			'youtube_url' => '',
			'instagram_url' => '',
			'prayer_cta_text' => '',
		);
	}
}