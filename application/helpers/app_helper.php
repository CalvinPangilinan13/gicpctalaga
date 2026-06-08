<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('setting_value'))
{
	function setting_value($settings, $key, $default = '')
	{
		return isset($settings[$key]) && $settings[$key] !== '' ? $settings[$key] : $default;
	}
}

if (!function_exists('is_active_menu'))
{
	function is_active_menu($uri)
	{
		$ci = get_instance();
		$current = trim($ci->uri->uri_string(), '/');

		return $current === trim($uri, '/') ? 'active' : '';
	}
}

if (!function_exists('asset_url'))
{
	function asset_url($path)
	{
		return base_url('assets/'.ltrim($path, '/'));
	}
}

if (!function_exists('media_url'))
{
	function media_url($path, $fallback = 'images/placeholders/default.svg')
	{
		if (empty($path))
		{
			return asset_url($fallback);
		}

		if (preg_match('/^https?:\/\//i', $path))
		{
			return $path;
		}

		return base_url(ltrim($path, '/'));
	}
}

if (!function_exists('formatted_date'))
{
	function formatted_date($date, $format = 'F j, Y')
	{
		if (empty($date))
		{
			return '';
		}

		return date($format, strtotime($date));
	}
}

if (!function_exists('excerpt'))
{
	function excerpt($value, $limit = 150)
	{
		return character_limiter(strip_tags((string) $value), $limit);
	}
}

if (!function_exists('status_badge_class'))
{
	function status_badge_class($status)
	{
		$map = array(
			'published' => 'success',
			'draft' => 'secondary',
			'archived' => 'dark',
			'featured' => 'warning',
			'pending' => 'warning',
			'approved' => 'success',
			'rejected' => 'danger',
		);

		return isset($map[$status]) ? $map[$status] : 'primary';
	}
}

if (!function_exists('slugify'))
{
	function slugify($value)
	{
		return url_title(convert_accented_characters((string) $value), 'dash', TRUE);
	}
}

if (!function_exists('module_label'))
{
	function module_label($value)
	{
		return ucwords(str_replace(array('_', '-'), ' ', $value));
	}
}

if (!function_exists('form_value'))
{
	function form_value($item, $field, $default = '')
	{
		if (is_object($item) && isset($item->{$field}))
		{
			return $item->{$field};
		}

		return $default;
	}
}

if (!function_exists('csrf_input'))
{
	function csrf_input()
	{
		$CI =& get_instance();

		return '<input type="hidden" name="'.html_escape($CI->security->get_csrf_token_name()).'" value="'.html_escape($CI->security->get_csrf_hash()).'">';
	}
}

if (!function_exists('admin_navigation'))
{
	function admin_navigation()
	{
		return array(
			'dashboard' => array('label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'url' => 'admin', 'section' => 'Overview'),
			'church-profile' => array('label' => 'Church Profile', 'icon' => 'bi-building', 'url' => 'admin/content/church_profile', 'section' => 'Content'),
			'pastors' => array('label' => 'Pastors', 'icon' => 'bi-people', 'url' => 'admin/content/pastors', 'section' => 'Content'),
			'ministries' => array('label' => 'Ministries', 'icon' => 'bi-grid', 'url' => 'admin/content/ministries', 'section' => 'Content'),
			'sermons' => array('label' => 'Sermons', 'icon' => 'bi-mic', 'url' => 'admin/content/sermons', 'section' => 'Content'),
			'events' => array('label' => 'Events', 'icon' => 'bi-calendar-event', 'url' => 'admin/content/events', 'section' => 'Content'),
			'announcements' => array('label' => 'Announcements', 'icon' => 'bi-megaphone', 'url' => 'admin/content/announcements', 'section' => 'Content'),
			'daily-verses' => array('label' => 'Daily Verses', 'icon' => 'bi-book', 'url' => 'admin/content/daily_verses', 'section' => 'Content'),
			'news-updates' => array('label' => 'News & Updates', 'icon' => 'bi-newspaper', 'url' => 'admin/content/news_updates', 'section' => 'Content'),
			'galleries' => array('label' => 'Galleries', 'icon' => 'bi-images', 'url' => 'admin/content/galleries', 'section' => 'Content'),
			'service-schedules' => array('label' => 'Schedules', 'icon' => 'bi-clock', 'url' => 'admin/content/service_schedules', 'section' => 'Engagement'),
			'event-registrations' => array('label' => 'Registrations', 'icon' => 'bi-person-check', 'url' => 'admin/content/event_registrations', 'section' => 'Engagement'),
			'prayer-requests' => array('label' => 'Prayer Requests', 'icon' => 'bi-heart', 'url' => 'admin/content/prayer_requests', 'section' => 'Engagement'),
			'contact-messages' => array('label' => 'Contact Messages', 'icon' => 'bi-chat-left-text', 'url' => 'admin/content/contact_messages', 'section' => 'Engagement'),
			'newsletter' => array('label' => 'Newsletter', 'icon' => 'bi-envelope-paper', 'url' => 'admin/content/newsletter_subscriptions', 'section' => 'Engagement'),
			'users' => array('label' => 'Users', 'icon' => 'bi-person-gear', 'url' => 'admin/content/users', 'section' => 'Administration'),
			'menu-items' => array('label' => 'Menu Items', 'icon' => 'bi-list', 'url' => 'admin/content/menu_items', 'section' => 'Administration'),
			'settings' => array('label' => 'Website Settings', 'icon' => 'bi-sliders', 'url' => 'admin/content/settings', 'section' => 'Administration'),
			'logs' => array('label' => 'Activity Logs', 'icon' => 'bi-journal-text', 'url' => 'admin/content/activity_logs', 'section' => 'Administration'),
		);
	}
}