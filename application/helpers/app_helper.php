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
			'dashboard' => array('label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'url' => 'admin', 'permission' => 'dashboard'),
			'church-profile' => array('label' => 'Church Profile', 'icon' => 'bi-building', 'url' => 'admin/content/church_profile', 'permission' => 'church_profile'),
			'pastors' => array('label' => 'Pastors', 'icon' => 'bi-people', 'url' => 'admin/content/pastors', 'permission' => 'pastors'),
			'ministries' => array('label' => 'Ministries', 'icon' => 'bi-grid', 'url' => 'admin/content/ministries', 'permission' => 'ministries'),
			'sermons' => array('label' => 'Sermons', 'icon' => 'bi-mic', 'url' => 'admin/content/sermons', 'permission' => 'sermons'),
			'events' => array('label' => 'Events', 'icon' => 'bi-calendar-event', 'url' => 'admin/content/events', 'permission' => 'events'),
			'announcements' => array('label' => 'Announcements', 'icon' => 'bi-megaphone', 'url' => 'admin/content/announcements', 'permission' => 'announcements'),
			'daily-verses' => array('label' => 'Daily Verses', 'icon' => 'bi-book', 'url' => 'admin/content/daily_verses', 'permission' => 'daily_verses'),
			'news-updates' => array('label' => 'News & Updates', 'icon' => 'bi-newspaper', 'url' => 'admin/content/news_updates', 'permission' => 'news_updates'),
			'galleries' => array('label' => 'Galleries', 'icon' => 'bi-images', 'url' => 'admin/content/galleries', 'permission' => 'galleries'),
			'users' => array('label' => 'Users', 'icon' => 'bi-person-gear', 'url' => 'admin/content/users', 'permission' => 'users'),
			'roles' => array('label' => 'Roles', 'icon' => 'bi-shield-lock', 'url' => 'admin/content/roles', 'permission' => 'roles'),
			'menu-items' => array('label' => 'Menu Items', 'icon' => 'bi-list', 'url' => 'admin/content/menu_items', 'permission' => 'menu_items'),
			'service-schedules' => array('label' => 'Schedules', 'icon' => 'bi-clock', 'url' => 'admin/content/service_schedules', 'permission' => 'service_schedules'),
			'event-registrations' => array('label' => 'Registrations', 'icon' => 'bi-person-check', 'url' => 'admin/content/event_registrations', 'permission' => 'event_registrations'),
			'prayer-requests' => array('label' => 'Prayer Requests', 'icon' => 'bi-heart', 'url' => 'admin/content/prayer_requests', 'permission' => 'prayer_requests'),
			'contact-messages' => array('label' => 'Contact Messages', 'icon' => 'bi-chat-left-text', 'url' => 'admin/content/contact_messages', 'permission' => 'contact_messages'),
			'newsletter' => array('label' => 'Newsletter', 'icon' => 'bi-envelope-paper', 'url' => 'admin/content/newsletter_subscriptions', 'permission' => 'newsletter_subscriptions'),
			'settings' => array('label' => 'Website Settings', 'icon' => 'bi-sliders', 'url' => 'admin/content/settings', 'permission' => 'settings'),
			'logs' => array('label' => 'Activity Logs', 'icon' => 'bi-journal-text', 'url' => 'admin/content/activity_logs', 'permission' => 'activity_logs'),
		);
	}
}

if (!function_exists('admin_permission_groups'))
{
	function admin_permission_groups()
	{
		return array(
			'content' => array(
				'church_profile',
				'pastors',
				'ministries',
				'sermons',
				'events',
				'announcements',
				'daily_verses',
				'news_updates',
				'galleries',
				'menu_items',
				'service_schedules',
			),
			'engagement' => array(
				'event_registrations',
				'prayer_requests',
				'contact_messages',
				'newsletter_subscriptions',
			),
			'administration' => array(
				'users',
				'roles',
				'settings',
				'activity_logs',
			),
		);
	}
}

if (!function_exists('admin_permission_options'))
{
	function admin_permission_options()
	{
		return array(
			'dashboard' => 'Dashboard',
			'church_profile' => 'Church Profile',
			'pastors' => 'Pastors',
			'ministries' => 'Ministries',
			'sermons' => 'Sermons',
			'events' => 'Events',
			'announcements' => 'Announcements',
			'daily_verses' => 'Daily Verses',
			'news_updates' => 'News & Updates',
			'galleries' => 'Galleries',
			'menu_items' => 'Menu Items',
			'service_schedules' => 'Service Schedules',
			'event_registrations' => 'Event Registrations',
			'prayer_requests' => 'Prayer Requests',
			'contact_messages' => 'Contact Messages',
			'newsletter_subscriptions' => 'Newsletter Subscriptions',
			'users' => 'Users',
			'roles' => 'Roles',
			'settings' => 'Website Settings',
			'activity_logs' => 'Activity Logs',
		);
	}
}

if (!function_exists('normalize_role_permissions'))
{
	function normalize_role_permissions($permissions, $expand_groups = FALSE)
	{
		if ($permissions === NULL || $permissions === '')
		{
			return NULL;
		}

		if (is_string($permissions))
		{
			$decoded = json_decode($permissions, TRUE);
			$permissions = json_last_error() === JSON_ERROR_NONE ? $decoded : array();
		}

		if (!is_array($permissions))
		{
			$permissions = array();
		}

		$normalized = array();

		foreach ($permissions as $permission)
		{
			$permission = trim((string) $permission);

			if ($permission === '')
			{
				continue;
			}

			$normalized[] = $permission;
		}

		$normalized = array_values(array_unique($normalized));

		if (!$expand_groups)
		{
			return $normalized;
		}

		$expanded = $normalized;
		$groups = admin_permission_groups();

		foreach ($normalized as $permission)
		{
			if ($permission === 'all')
			{
				$expanded = array_merge(array('dashboard'), array_keys(admin_permission_options()));
				break;
			}

			if (isset($groups[$permission]))
			{
				$expanded = array_merge($expanded, $groups[$permission]);
			}
		}

		return array_values(array_unique($expanded));
	}
}

if (!function_exists('role_has_permission'))
{
	function role_has_permission($permissions, $required_permission)
	{
		$normalized = normalize_role_permissions($permissions);

		if ($normalized === NULL)
		{
			return TRUE;
		}

		if (in_array('all', $normalized, TRUE) || in_array($required_permission, $normalized, TRUE))
		{
			return TRUE;
		}

		$groups = admin_permission_groups();

		foreach ($groups as $group_key => $group_permissions)
		{
			if (in_array($group_key, $normalized, TRUE) && in_array($required_permission, $group_permissions, TRUE))
			{
				return TRUE;
			}
		}

		return FALSE;
	}
}

if (!function_exists('admin_first_accessible_url'))
{
	function admin_first_accessible_url($permissions)
	{
		foreach (admin_navigation() as $item)
		{
			$required_permission = isset($item['permission']) ? $item['permission'] : NULL;

			if ($required_permission === NULL || role_has_permission($permissions, $required_permission))
			{
				return $item['url'];
			}
		}

		return 'admin/change-password';
	}
}