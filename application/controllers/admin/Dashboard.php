<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Pastor_model $pastor_model
 * @property Ministry_model $ministry_model
 * @property Sermon_model $sermon_model
 * @property Event_model $event_model
 * @property Announcement_model $announcement_model
 * @property News_update_model $news_update_model
 * @property Gallery_model $gallery_model
 * @property Prayer_request_model $prayer_request_model
 * @property Newsletter_subscription_model $newsletter_subscription_model
 * @property Contact_message_model $contact_message_model
 * @property Event_registration_model $event_registration_model
 */
class Dashboard extends Admin_Controller
{
	/** @var Pastor_model */
	public $pastor_model;
	/** @var Ministry_model */
	public $ministry_model;
	/** @var Sermon_model */
	public $sermon_model;
	/** @var Event_model */
	public $event_model;
	/** @var Announcement_model */
	public $announcement_model;
	/** @var News_update_model */
	public $news_update_model;
	/** @var Gallery_model */
	public $gallery_model;
	/** @var Prayer_request_model */
	public $prayer_request_model;
	/** @var Newsletter_subscription_model */
	public $newsletter_subscription_model;
	/** @var Contact_message_model */
	public $contact_message_model;
	/** @var Event_registration_model */
	public $event_registration_model;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'pastor_model',
			'ministry_model',
			'sermon_model',
			'event_model',
			'announcement_model',
			'news_update_model',
			'gallery_model',
			'prayer_request_model',
			'newsletter_subscription_model',
			'contact_message_model',
			'event_registration_model'
		));
	}

	public function index()
	{
		$this->authorize_admin_access('dashboard');
		$stat_definitions = array(
			array('permission' => 'pastors', 'label' => 'Pastors', 'value' => $this->pastor_model->count_all(), 'icon' => 'bi-people'),
			array('permission' => 'ministries', 'label' => 'Ministries', 'value' => $this->ministry_model->count_all(), 'icon' => 'bi-grid'),
			array('permission' => 'sermons', 'label' => 'Sermons', 'value' => $this->sermon_model->count_all(), 'icon' => 'bi-mic'),
			array('permission' => 'events', 'label' => 'Events', 'value' => $this->event_model->count_all(), 'icon' => 'bi-calendar-event'),
			array('permission' => 'announcements', 'label' => 'Announcements', 'value' => $this->announcement_model->count_all(), 'icon' => 'bi-megaphone'),
			array('permission' => 'news_updates', 'label' => 'News Posts', 'value' => $this->news_update_model->count_all(), 'icon' => 'bi-newspaper'),
			array('permission' => 'galleries', 'label' => 'Gallery Albums', 'value' => $this->gallery_model->count_all(), 'icon' => 'bi-images'),
			array('permission' => 'users', 'label' => 'Users', 'value' => $this->user_model->count_all(), 'icon' => 'bi-person-gear'),
		);
		$stats = array();

		foreach ($stat_definitions as $stat)
		{
			if ($this->has_admin_access($stat['permission']))
			{
				$stats[] = array(
					'label' => $stat['label'],
					'value' => $stat['value'],
					'icon' => $stat['icon'],
				);
			}
		}

		$can_view_activity_logs = $this->has_admin_access('activity_logs');
		$can_view_events = $this->has_admin_access('events');
		$can_view_prayer_requests = $this->has_admin_access('prayer_requests');
		$can_view_contact_messages = $this->has_admin_access('contact_messages');
		$can_view_event_registrations = $this->has_admin_access('event_registrations');
		$can_view_newsletter_subscriptions = $this->has_admin_access('newsletter_subscriptions');

		$data = array(
			'page_title' => 'Dashboard',
			'stats' => $stats,
			'quick_links' => isset($this->admin_data['admin_menu']) ? $this->admin_data['admin_menu'] : admin_navigation(),
			'can_view_activity_logs' => $can_view_activity_logs,
			'can_view_events' => $can_view_events,
			'can_view_prayer_requests' => $can_view_prayer_requests,
			'can_view_contact_messages' => $can_view_contact_messages,
			'can_view_event_registrations' => $can_view_event_registrations,
			'can_view_newsletter_subscriptions' => $can_view_newsletter_subscriptions,
			'latest_activities' => $can_view_activity_logs ? $this->activity_log_model->get_recent(8) : array(),
			'upcoming_events' => $can_view_events ? $this->event_model->get_upcoming(5) : array(),
			'pending_requests' => $can_view_prayer_requests ? $this->prayer_request_model->get_latest(5) : array(),
			'new_messages' => $can_view_contact_messages ? $this->contact_message_model->get_latest(5) : array(),
			'registration_count' => $can_view_event_registrations ? $this->event_registration_model->count_all() : 0,
			'subscription_count' => $can_view_newsletter_subscriptions ? $this->newsletter_subscription_model->count_all() : 0,
		);

		$this->render('admin/dashboard/index', $data);
	}
}