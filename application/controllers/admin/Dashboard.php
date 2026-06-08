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
		$data = array(
			'page_title' => 'Dashboard',
			'stats' => array(
				array('label' => 'Pastors', 'value' => $this->pastor_model->count_all(), 'icon' => 'bi-people'),
				array('label' => 'Ministries', 'value' => $this->ministry_model->count_all(), 'icon' => 'bi-grid'),
				array('label' => 'Sermons', 'value' => $this->sermon_model->count_all(), 'icon' => 'bi-mic'),
				array('label' => 'Events', 'value' => $this->event_model->count_all(), 'icon' => 'bi-calendar-event'),
				array('label' => 'Announcements', 'value' => $this->announcement_model->count_all(), 'icon' => 'bi-megaphone'),
				array('label' => 'News Posts', 'value' => $this->news_update_model->count_all(), 'icon' => 'bi-newspaper'),
				array('label' => 'Gallery Albums', 'value' => $this->gallery_model->count_all(), 'icon' => 'bi-images'),
				array('label' => 'Users', 'value' => $this->user_model->count_all(), 'icon' => 'bi-person-gear'),
			),
			'quick_links' => admin_navigation(),
			'latest_activities' => $this->activity_log_model->get_recent(8),
			'upcoming_events' => $this->event_model->get_upcoming(5),
			'pending_requests' => $this->prayer_request_model->get_latest(5),
			'new_messages' => $this->contact_message_model->get_latest(5),
			'registration_count' => $this->event_registration_model->count_all(),
			'subscription_count' => $this->newsletter_subscription_model->count_all(),
		);

		$this->render('admin/dashboard/index', $data);
	}
}