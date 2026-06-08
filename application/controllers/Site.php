<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Gallery_image_model $gallery_image_model
 * @property Sermon_file_model $sermon_file_model
 * @property Event_registration_model $event_registration_model
 * @property Prayer_request_model $prayer_request_model
 * @property Newsletter_subscription_model $newsletter_subscription_model
 * @property Contact_message_model $contact_message_model
 */
class Site extends Site_Controller
{
	/** @var Gallery_image_model */
	public $gallery_image_model;
	/** @var Sermon_file_model */
	public $sermon_file_model;
	/** @var Event_registration_model */
	public $event_registration_model;
	/** @var Prayer_request_model */
	public $prayer_request_model;
	/** @var Newsletter_subscription_model */
	public $newsletter_subscription_model;
	/** @var Contact_message_model */
	public $contact_message_model;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'gallery_image_model',
			'sermon_file_model',
			'event_registration_model',
			'prayer_request_model',
			'newsletter_subscription_model',
			'contact_message_model'
		));
	}

	public function index()
	{
		$data = array(
			'page_title' => 'Home',
			'meta_description' => setting_value($this->site_data['settings'], 'meta_description', ''),
			'hero_title' => setting_value($this->site_data['settings'], 'hero_heading', 'Welcome to GICP Talaga'),
			'hero_subtitle' => setting_value($this->site_data['settings'], 'hero_subheading', $this->site_data['church_profile']->tagline),
			'hero_button_text' => setting_value($this->site_data['settings'], 'hero_button_text', 'Join Us This Sunday'),
			'hero_button_link' => setting_value($this->site_data['settings'], 'hero_button_link', 'contact'),
			'featured_pastors' => $this->pastor_model->get_featured(4),
			'featured_ministries' => $this->ministry_model->get_featured(6),
			'upcoming_events' => $this->event_model->get_upcoming(3),
			'featured_announcements' => $this->announcement_model->get_featured(3),
			'latest_news' => $this->news_update_model->get_featured(3),
			'latest_sermons' => $this->sermon_model->get_published('', 3),
			'featured_albums' => $this->gallery_model->get_featured(3),
			'visitor_count' => setting_value($this->site_data['settings'], 'visitor_count', '0'),
		);

		$this->render('site/home', $data);
	}

	public function about()
	{
		$this->render('site/about', array('page_title' => 'About Us'));
	}

	public function pastors()
	{
		$this->render('site/pastors', array(
			'page_title' => 'Pastors & Leaders',
			'pastors' => $this->pastor_model->get_all(),
		));
	}

	public function ministries()
	{
		$this->render('site/ministries', array(
			'page_title' => 'Ministries',
			'ministries' => $this->ministry_model->get_all(),
		));
	}

	public function sermons()
	{
		$query = trim((string) $this->input->get('q', TRUE));

		$this->render('site/sermons', array(
			'page_title' => 'Sermons',
			'search_term' => $query,
			'sermons' => $this->sermon_model->get_published($query),
		));
	}

	public function sermon($slug)
	{
		$sermon = $this->sermon_model->get_by_slug($slug);

		if (!$sermon)
		{
			show_404();
		}

		$this->render('site/sermon_detail', array(
			'page_title' => $sermon->title,
			'sermon' => $sermon,
			'sermon_files' => $this->sermon_file_model->get_by_sermon($sermon->id),
		));
	}

	public function events()
	{
		$query = trim((string) $this->input->get('q', TRUE));

		$this->render('site/events', array(
			'page_title' => 'Events',
			'search_term' => $query,
			'events' => $this->event_model->get_published($query),
		));
	}

	public function event($slug)
	{
		$event = $this->event_model->get_by_slug($slug);

		if (!$event)
		{
			show_404();
		}

		$this->render('site/event_detail', array(
			'page_title' => $event->title,
			'event' => $event,
			'registration_count' => $this->event_registration_model->count_for_event($event->id),
		));
	}

	public function event_register($id)
	{
		if (strtoupper($this->input->method()) !== 'POST')
		{
			show_404();
		}

		$event = $this->event_model->get_by_id($id);

		if (!$event || (int) $event->registration_enabled !== 1)
		{
			show_404();
		}

		$this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('site_error', validation_errors('<div>', '</div>'));
			redirect('events/'.$event->slug.'#registration');
		}

		if (!empty($event->registration_limit) && $this->event_registration_model->count_for_event($event->id) >= (int) $event->registration_limit)
		{
			$this->session->set_flashdata('site_error', 'Registration is already full for this event.');
			redirect('events/'.$event->slug.'#registration');
		}

		$this->event_registration_model->insert(array(
			'event_id' => $event->id,
			'full_name' => $this->input->post('full_name', TRUE),
			'email' => $this->input->post('email', TRUE),
			'mobile_number' => $this->input->post('mobile_number', TRUE),
			'notes' => $this->input->post('notes', TRUE),
			'created_at' => date('Y-m-d H:i:s'),
		));

		$this->session->set_flashdata('site_success', 'Your event registration was received.');
		redirect('events/'.$event->slug.'#registration');
	}

	public function announcements()
	{
		$announcements = $this->announcement_model->get_published();
		$archives = array();

		foreach ($announcements as $announcement)
		{
			$key = date('F Y', strtotime($announcement->publish_date));
			$archives[$key] = isset($archives[$key]) ? $archives[$key] + 1 : 1;
		}

		$this->render('site/announcements', array(
			'page_title' => 'Announcements',
			'announcements' => $announcements,
			'archives' => $archives,
		));
	}

	public function announcement($slug)
	{
		$announcement = $this->announcement_model->get_by_slug($slug);

		if (!$announcement)
		{
			show_404();
		}

		$this->render('site/announcement_detail', array(
			'page_title' => $announcement->title,
			'announcement' => $announcement,
		));
	}

	public function news()
	{
		$query = trim((string) $this->input->get('q', TRUE));

		$this->render('site/news', array(
			'page_title' => 'News & Updates',
			'search_term' => $query,
			'featured_news' => $this->news_update_model->get_featured(3),
			'news_items' => $this->news_update_model->get_published($query),
		));
	}

	public function news_item($slug)
	{
		$item = $this->news_update_model->get_by_slug($slug);

		if (!$item)
		{
			show_404();
		}

		$this->render('site/news_detail', array(
			'page_title' => $item->title,
			'news_item' => $item,
		));
	}

	public function gallery()
	{
		$this->render('site/gallery', array(
			'page_title' => 'Gallery',
			'galleries' => $this->gallery_model->get_published(),
		));
	}

	public function gallery_album($slug)
	{
		$gallery = $this->gallery_model->get_by_slug($slug);

		if (!$gallery)
		{
			show_404();
		}

		$this->render('site/gallery_detail', array(
			'page_title' => $gallery->title,
			'gallery' => $gallery,
			'images' => $this->gallery_image_model->get_by_gallery($gallery->id),
		));
	}

	public function contact()
	{
		if (strtoupper($this->input->method()) === 'POST')
		{
			$this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
			$this->form_validation->set_rules('message', 'Message', 'trim|required');

			if ($this->form_validation->run())
			{
				$this->contact_message_model->insert(array(
					'full_name' => $this->input->post('full_name', TRUE),
					'email' => $this->input->post('email', TRUE),
					'mobile_number' => $this->input->post('mobile_number', TRUE),
					'subject' => $this->input->post('subject', TRUE),
					'message' => $this->input->post('message', TRUE),
					'status' => 'new',
					'created_at' => date('Y-m-d H:i:s'),
				));

				$this->session->set_flashdata('site_success', 'Your message has been sent.');
				redirect('contact');
			}

			$this->session->set_flashdata('site_error', validation_errors('<div>', '</div>'));
		}

		$this->render('site/contact', array('page_title' => 'Contact'));
	}

	public function prayer_request()
	{
		if (strtoupper($this->input->method()) !== 'POST')
		{
			show_404();
		}

		$this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('request_text', 'Prayer Request', 'trim|required');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('site_error', validation_errors('<div>', '</div>'));
			$this->redirect_back('contact');
		}

		$this->prayer_request_model->insert(array(
			'full_name' => $this->input->post('full_name', TRUE),
			'email' => $this->input->post('email', TRUE),
			'mobile_number' => $this->input->post('mobile_number', TRUE),
			'request_text' => $this->input->post('request_text', TRUE),
			'is_private' => $this->input->post('is_private') ? 1 : 0,
			'status' => 'pending',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		));

		$this->session->set_flashdata('site_success', 'Your prayer request has been submitted.');
		$this->redirect_back('contact');
	}

	public function newsletter_subscribe()
	{
		if (strtoupper($this->input->method()) !== 'POST')
		{
			show_404();
		}

		$email = trim((string) $this->input->post('email', TRUE));
		$this->form_validation->set_data(array('email' => $email));
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('site_error', validation_errors('<div>', '</div>'));
			$this->redirect_back('');
		}

		$existing = $this->newsletter_subscription_model->get_by(array('email' => $email), TRUE);

		if ($existing)
		{
			$this->session->set_flashdata('site_success', 'This email address is already subscribed.');
			$this->redirect_back('');
		}

		$this->newsletter_subscription_model->insert(array(
			'email' => $email,
			'status' => 'subscribed',
			'created_at' => date('Y-m-d H:i:s'),
		));

		$this->session->set_flashdata('site_success', 'Thank you for subscribing to our updates.');
		$this->redirect_back('');
	}

	public function search()
	{
		$query = trim((string) $this->input->get('q', TRUE));
		$results = array(
			'sermons' => array(),
			'events' => array(),
			'announcements' => array(),
			'news_items' => array(),
		);

		if ($query !== '')
		{
			$results['sermons'] = $this->sermon_model->get_published($query);
			$results['events'] = $this->event_model->get_published($query);
			$results['announcements'] = $this->announcement_model->get_published($query);
			$results['news_items'] = $this->news_update_model->get_published($query);
		}

		$this->render('site/search', array(
			'page_title' => 'Search',
			'query' => $query,
			'results' => $results,
		));
	}

	protected function redirect_back($fallback = '')
	{
		$redirect = $this->input->post('redirect_url', TRUE);

		if (empty($redirect))
		{
			$redirect = $fallback;
		}

		redirect($redirect);
	}
}