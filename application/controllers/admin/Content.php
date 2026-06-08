<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property Gallery_image_model $gallery_image_model
 * @property Sermon_file_model $sermon_file_model
 * @property Role_model $role_model
 */
class Content extends Admin_Controller
{
	protected $modules = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'church_profile_model',
			'pastor_model',
			'ministry_model',
			'sermon_model',
			'sermon_file_model',
			'event_model',
			'event_registration_model',
			'announcement_model',
			'daily_verse_model',
			'news_update_model',
			'gallery_model',
			'gallery_image_model',
			'prayer_request_model',
			'newsletter_subscription_model',
			'contact_message_model',
			'service_schedule_model'
		));

		$this->modules = $this->module_configs();
	}

	public function index($module = 'church_profile')
	{
		$config = $this->get_module_config($module);

		if (!empty($config['single']))
		{
			$item = $this->{$config['model']}->get_primary_profile();
			$target_id = isset($item->id) ? $item->id : 1;
			redirect('admin/content/'.$module.'/edit/'.$target_id);
		}

		$data = array(
			'page_title' => $config['title'],
			'module' => $module,
			'config' => $config,
			'items' => $this->fetch_module_items($config),
		);

		$this->render('admin/content/list', $data);
	}

	public function create($module)
	{
		$config = $this->get_module_config($module);

		if (!empty($config['single']) || (isset($config['allow_create']) && !$config['allow_create']))
		{
			redirect('admin/content/'.$module);
		}

		$item = (object) array();

		if (strtoupper($this->input->method()) === 'POST')
		{
			if ($this->save_module($module, NULL))
			{
				return;
			}
		}

		$this->render_form($module, $config, $item, 'create');
	}

	public function edit($module, $id)
	{
		$config = $this->get_module_config($module);
		$model = $config['model'];

		$item = !empty($config['single']) ? $this->{$model}->get_primary_profile() : $this->{$model}->get_by_id($id);

		if (!$item && empty($config['single']))
		{
			show_404();
		}

		if (strtoupper($this->input->method()) === 'POST')
		{
			if ($this->save_module($module, $id, $item))
			{
				return;
			}
		}

		$this->render_form($module, $config, $item, 'edit');
	}

	public function delete($module, $id)
	{
		$config = $this->get_module_config($module);
		$model = $config['model'];

		if (!empty($config['single']) || (isset($config['allow_delete']) && !$config['allow_delete']))
		{
			redirect('admin/content/'.$module);
		}

		$item = $this->{$model}->get_by_id($id);

		if (!$item)
		{
			show_404();
		}

		$this->{$model}->delete($id);
		$this->log_activity('delete', $module, 'Deleted '.strtolower($config['title']).' record #'.$id.'.');
		$this->session->set_flashdata('admin_success', $config['title'].' item deleted successfully.');
		redirect('admin/content/'.$module);
	}

	protected function render_form($module, $config, $item, $mode)
	{
		$data = array(
			'page_title' => ($mode === 'create' ? 'Create ' : 'Edit ').$config['title'],
			'module' => $module,
			'mode' => $mode,
			'config' => $config,
			'item' => $item,
			'fields' => $this->prepare_fields($module, $config),
			'gallery_images' => $module === 'galleries' && !empty($item->id) ? $this->gallery_image_model->get_by_gallery($item->id) : array(),
			'sermon_files' => $module === 'sermons' && !empty($item->id) ? $this->sermon_file_model->get_by_sermon($item->id) : array(),
		);

		$this->render('admin/content/form', $data);
	}

	protected function save_module($module, $id = NULL, $existing = NULL)
	{
		$config = $this->get_module_config($module);
		$model = $config['model'];
		$payload = array();
		$timestamp = date('Y-m-d H:i:s');

		foreach ($config['fields'] as $field)
		{
			if (!empty($field['required']) && $field['type'] !== 'file')
			{
				$this->form_validation->set_rules($field['name'], $field['label'], 'trim|required');
			}
		}

		if (!$this->form_validation->run())
		{
			return FALSE;
		}

		foreach ($config['fields'] as $field)
		{
			if (isset($field['persist']) && $field['persist'] === FALSE)
			{
				continue;
			}

			$name = $field['name'];

			if ($field['type'] === 'checkbox')
			{
				$payload[$name] = $this->input->post($name) ? 1 : 0;
				continue;
			}

			if ($field['type'] === 'password')
			{
				$value = $this->input->post($name, FALSE);

				if ($value !== '')
				{
					$payload[$name] = $value;
				}

				continue;
			}

			if ($field['type'] === 'file')
			{
				$upload = $this->upload_file($name, $field['upload']['path'], $field['upload']['types']);

				if (isset($upload['error']))
				{
					$this->session->set_flashdata('admin_error', $upload['error']);
					return FALSE;
				}

				if (isset($upload['path']))
				{
					$payload[$name] = $upload['path'];
				}

				continue;
			}

			$value = $this->input->post($name, FALSE);
			$payload[$name] = $this->normalize_value($field, $value);
		}

		if (!empty($config['slug_field']))
		{
			$source = $this->input->post($config['slug_source'], TRUE);
			$slug = trim((string) $this->input->post($config['slug_field'], TRUE));
			$payload[$config['slug_field']] = $this->make_slug($slug !== '' ? $slug : $source);
		}

		$payload['updated_at'] = $timestamp;

		if ($id === NULL)
		{
			$payload['created_at'] = $timestamp;
		}

		if (!empty($config['single']) && $existing && !empty($existing->id))
		{
			$id = $existing->id;
		}

		if (!empty($config['save_method']) && method_exists($this->{$model}, $config['save_method']))
		{
			$record_id = $this->{$model}->{$config['save_method']}($payload, $id);
		}
		else
		{
			$record_id = $this->{$model}->save($payload, $id);
		}

		if (!empty($config['after_save']) && method_exists($this, $config['after_save']))
		{
			$result = $this->{$config['after_save']}($record_id, $existing);

			if ($result === FALSE)
			{
				return FALSE;
			}
		}

		$this->log_activity($id === NULL ? 'create' : 'update', $module, ($id === NULL ? 'Created ' : 'Updated ').strtolower($config['title']).' record #'.$record_id.'.');
		$this->session->set_flashdata('admin_success', $config['title'].' saved successfully.');

		if (!empty($config['single']))
		{
			redirect('admin/content/'.$module.'/edit/'.$record_id);
		}

		redirect('admin/content/'.$module);
	}

	protected function normalize_value($field, $value)
	{
		if ($value === NULL)
		{
			return NULL;
		}

		$value = trim((string) $value);

		if ($field['type'] === 'datetime')
		{
			return $value === '' ? NULL : str_replace('T', ' ', $value).':00';
		}

		return $value;
	}

	protected function prepare_fields($module, $config)
	{
		$fields = $config['fields'];

		foreach ($fields as &$field)
		{
			if (!empty($field['options_callback']) && method_exists($this, $field['options_callback']))
			{
				$field['options'] = $this->{$field['options_callback']}();
			}
		}

		return $fields;
	}

	protected function fetch_module_items($config)
	{
		if (!empty($config['list_callback']) && method_exists($this, $config['list_callback']))
		{
			return $this->{$config['list_callback']}();
		}

		$model = $config['model'];

		if (!empty($config['list_method']) && method_exists($this->{$model}, $config['list_method']))
		{
			return $this->{$model}->{$config['list_method']}();
		}

		return $this->{$model}->get_all();
	}

	protected function list_event_registrations()
	{
		return $this->db
			->select('event_registrations.*, events.title AS event_title')
			->from('event_registrations')
			->join('events', 'events.id = event_registrations.event_id', 'left')
			->order_by('event_registrations.created_at', 'DESC')
			->get()
			->result();
	}

	protected function list_activity_logs()
	{
		return $this->activity_log_model->get_recent(50);
	}

	protected function get_role_options()
	{
		return $this->role_model->get_for_dropdown('id', 'name', 'Select role');
	}

	protected function get_menu_parent_options()
	{
		return $this->menu_model->get_for_dropdown('id', 'label', 'No parent');
	}

	protected function after_save_sermons($record_id)
	{
		$files = array(
			'audio_upload' => 'audio',
			'pdf_upload' => 'pdf',
		);

		foreach ($files as $field_name => $type)
		{
			$upload = $this->upload_file($field_name, 'uploads/sermons/', $type === 'audio' ? 'mp3|wav|ogg|m4a' : 'pdf');

			if (isset($upload['error']))
			{
				$this->session->set_flashdata('admin_error', $upload['error']);
				return FALSE;
			}

			if (!isset($upload['path']))
			{
				continue;
			}

			$this->db->delete('sermon_files', array('sermon_id' => $record_id, 'file_type' => $type));
			$this->sermon_file_model->insert(array(
				'sermon_id' => $record_id,
				'file_type' => $type,
				'file_path' => $upload['path'],
				'original_name' => $upload['original_name'],
				'created_at' => date('Y-m-d H:i:s'),
			));
		}

		return TRUE;
	}

	protected function after_save_galleries($record_id)
	{
		$uploads = $this->upload_multiple_files('gallery_images', 'uploads/galleries/', 'gif|jpg|jpeg|png|webp');

		if (isset($uploads['error']))
		{
			$this->session->set_flashdata('admin_error', $uploads['error']);
			return FALSE;
		}

		$order = 1;

		foreach ($uploads as $upload)
		{
			$this->gallery_image_model->insert(array(
				'gallery_id' => $record_id,
				'image_path' => $upload['path'],
				'caption' => $upload['original_name'],
				'display_order' => $order++,
				'created_at' => date('Y-m-d H:i:s'),
			));
		}

		return TRUE;
	}

	protected function get_module_config($module)
	{
		if (!isset($this->modules[$module]))
		{
			show_404();
		}

		return $this->modules[$module];
	}

	protected function make_slug($value)
	{
		$value = strtolower(trim((string) $value));
		$value = preg_replace('/[^a-z0-9]+/i', '-', $value);

		return trim((string) $value, '-');
	}

	protected function module_configs()
	{
		$status_options = array('draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived');

		return array(
			'church_profile' => array(
				'title' => 'Church Profile',
				'model' => 'church_profile_model',
				'single' => TRUE,
				'fields' => array(
					array('name' => 'church_name', 'label' => 'Church Name', 'type' => 'text', 'required' => TRUE),
					array('name' => 'short_name', 'label' => 'Short Name', 'type' => 'text', 'required' => TRUE),
					array('name' => 'tagline', 'label' => 'Tagline', 'type' => 'text'),
					array('name' => 'logo', 'label' => 'Logo', 'type' => 'file', 'upload' => array('path' => 'uploads/profile/', 'types' => 'gif|jpg|jpeg|png|svg|webp')),
					array('name' => 'hero_image', 'label' => 'Hero Image', 'type' => 'file', 'upload' => array('path' => 'uploads/profile/', 'types' => 'gif|jpg|jpeg|png|svg|webp')),
					array('name' => 'welcome_message', 'label' => 'Welcome Message', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'brief_intro', 'label' => 'Brief Introduction', 'type' => 'textarea'),
					array('name' => 'history', 'label' => 'History', 'type' => 'textarea'),
					array('name' => 'mission', 'label' => 'Mission', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'vision', 'label' => 'Vision', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'core_values', 'label' => 'Core Values', 'type' => 'textarea'),
					array('name' => 'statement_of_faith', 'label' => 'Statement of Faith', 'type' => 'textarea'),
					array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
					array('name' => 'email', 'label' => 'Email', 'type' => 'email'),
					array('name' => 'mobile_number', 'label' => 'Mobile Number', 'type' => 'text'),
					array('name' => 'landline', 'label' => 'Landline', 'type' => 'text'),
					array('name' => 'google_map_embed', 'label' => 'Google Map Embed', 'type' => 'textarea'),
					array('name' => 'facebook_url', 'label' => 'Facebook URL', 'type' => 'url'),
					array('name' => 'youtube_url', 'label' => 'YouTube URL', 'type' => 'url'),
					array('name' => 'instagram_url', 'label' => 'Instagram URL', 'type' => 'url'),
					array('name' => 'prayer_cta_text', 'label' => 'Prayer CTA Text', 'type' => 'text'),
				),
			),
			'pastors' => array(
				'title' => 'Pastors',
				'model' => 'pastor_model',
				'slug_field' => 'slug',
				'slug_source' => 'name',
				'columns' => array(
					array('label' => 'Photo', 'field' => 'photo', 'type' => 'image'),
					array('label' => 'Name', 'field' => 'name'),
					array('label' => 'Position', 'field' => 'position'),
					array('label' => 'Featured', 'field' => 'is_featured', 'type' => 'boolean'),
					array('label' => 'Order', 'field' => 'display_order'),
				),
				'fields' => array(
					array('name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => TRUE),
					array('name' => 'slug', 'label' => 'Slug', 'type' => 'text'),
					array('name' => 'position', 'label' => 'Position', 'type' => 'text', 'required' => TRUE),
					array('name' => 'bio', 'label' => 'Biography', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'photo', 'label' => 'Photo', 'type' => 'file', 'upload' => array('path' => 'uploads/pastors/', 'types' => 'gif|jpg|jpeg|png|webp|svg')),
					array('name' => 'is_featured', 'label' => 'Feature on homepage', 'type' => 'checkbox'),
					array('name' => 'display_order', 'label' => 'Display Order', 'type' => 'number'),
				),
			),
			'ministries' => array(
				'title' => 'Ministries',
				'model' => 'ministry_model',
				'slug_field' => 'slug',
				'slug_source' => 'name',
				'columns' => array(
					array('label' => 'Image', 'field' => 'image', 'type' => 'image'),
					array('label' => 'Name', 'field' => 'name'),
					array('label' => 'Leader', 'field' => 'leader_name'),
					array('label' => 'Featured', 'field' => 'is_featured', 'type' => 'boolean'),
				),
				'fields' => array(
					array('name' => 'name', 'label' => 'Ministry Name', 'type' => 'text', 'required' => TRUE),
					array('name' => 'slug', 'label' => 'Slug', 'type' => 'text'),
					array('name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'image', 'label' => 'Image', 'type' => 'file', 'upload' => array('path' => 'uploads/ministries/', 'types' => 'gif|jpg|jpeg|png|webp|svg')),
					array('name' => 'leader_name', 'label' => 'Leader Name', 'type' => 'text'),
					array('name' => 'meeting_schedule', 'label' => 'Meeting Schedule', 'type' => 'text'),
					array('name' => 'is_featured', 'label' => 'Feature on homepage', 'type' => 'checkbox'),
					array('name' => 'display_order', 'label' => 'Display Order', 'type' => 'number'),
				),
			),
			'sermons' => array(
				'title' => 'Sermons',
				'model' => 'sermon_model',
				'slug_field' => 'slug',
				'slug_source' => 'title',
				'after_save' => 'after_save_sermons',
				'columns' => array(
					array('label' => 'Image', 'field' => 'featured_image', 'type' => 'image'),
					array('label' => 'Title', 'field' => 'title'),
					array('label' => 'Speaker', 'field' => 'speaker'),
					array('label' => 'Date', 'field' => 'sermon_date', 'type' => 'date'),
					array('label' => 'Status', 'field' => 'status', 'type' => 'status'),
				),
				'fields' => array(
					array('name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => TRUE),
					array('name' => 'slug', 'label' => 'Slug', 'type' => 'text'),
					array('name' => 'speaker', 'label' => 'Speaker', 'type' => 'text', 'required' => TRUE),
					array('name' => 'sermon_date', 'label' => 'Sermon Date', 'type' => 'date', 'required' => TRUE),
					array('name' => 'excerpt', 'label' => 'Excerpt', 'type' => 'textarea'),
					array('name' => 'content', 'label' => 'Content', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'youtube_url', 'label' => 'YouTube URL', 'type' => 'url'),
					array('name' => 'featured_image', 'label' => 'Featured Image', 'type' => 'file', 'upload' => array('path' => 'uploads/sermons/', 'types' => 'gif|jpg|jpeg|png|webp|svg')),
					array('name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => $status_options),
					array('name' => 'audio_upload', 'label' => 'Audio File', 'type' => 'file', 'persist' => FALSE, 'upload' => array('path' => 'uploads/sermons/', 'types' => 'mp3|wav|ogg|m4a')),
					array('name' => 'pdf_upload', 'label' => 'PDF Notes', 'type' => 'file', 'persist' => FALSE, 'upload' => array('path' => 'uploads/sermons/', 'types' => 'pdf')),
				),
			),
			'events' => array(
				'title' => 'Events',
				'model' => 'event_model',
				'slug_field' => 'slug',
				'slug_source' => 'title',
				'columns' => array(
					array('label' => 'Image', 'field' => 'image', 'type' => 'image'),
					array('label' => 'Title', 'field' => 'title'),
					array('label' => 'Location', 'field' => 'location'),
					array('label' => 'Start', 'field' => 'start_datetime', 'type' => 'datetime'),
					array('label' => 'Status', 'field' => 'status', 'type' => 'status'),
				),
				'fields' => array(
					array('name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => TRUE),
					array('name' => 'slug', 'label' => 'Slug', 'type' => 'text'),
					array('name' => 'summary', 'label' => 'Summary', 'type' => 'textarea'),
					array('name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'location', 'label' => 'Location', 'type' => 'text'),
					array('name' => 'start_datetime', 'label' => 'Start', 'type' => 'datetime', 'required' => TRUE),
					array('name' => 'end_datetime', 'label' => 'End', 'type' => 'datetime'),
					array('name' => 'image', 'label' => 'Image', 'type' => 'file', 'upload' => array('path' => 'uploads/events/', 'types' => 'gif|jpg|jpeg|png|webp|svg')),
					array('name' => 'registration_enabled', 'label' => 'Enable registration', 'type' => 'checkbox'),
					array('name' => 'registration_limit', 'label' => 'Registration Limit', 'type' => 'number'),
					array('name' => 'is_featured', 'label' => 'Feature on homepage', 'type' => 'checkbox'),
					array('name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => $status_options),
				),
			),
			'announcements' => array(
				'title' => 'Announcements',
				'model' => 'announcement_model',
				'slug_field' => 'slug',
				'slug_source' => 'title',
				'columns' => array(
					array('label' => 'Title', 'field' => 'title'),
					array('label' => 'Publish Date', 'field' => 'publish_date', 'type' => 'date'),
					array('label' => 'Featured', 'field' => 'is_featured', 'type' => 'boolean'),
					array('label' => 'Status', 'field' => 'status', 'type' => 'status'),
				),
				'fields' => array(
					array('name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => TRUE),
					array('name' => 'slug', 'label' => 'Slug', 'type' => 'text'),
					array('name' => 'summary', 'label' => 'Summary', 'type' => 'textarea'),
					array('name' => 'content', 'label' => 'Content', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'image', 'label' => 'Image', 'type' => 'file', 'upload' => array('path' => 'uploads/announcements/', 'types' => 'gif|jpg|jpeg|png|webp|svg')),
					array('name' => 'publish_date', 'label' => 'Publish Date', 'type' => 'date', 'required' => TRUE),
					array('name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'),
					array('name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => $status_options),
				),
			),
			'daily_verses' => array(
				'title' => 'Daily Verses',
				'model' => 'daily_verse_model',
				'columns' => array(
					array('label' => 'Reference', 'field' => 'reference'),
					array('label' => 'Publish Date', 'field' => 'publish_date', 'type' => 'date'),
					array('label' => 'Published', 'field' => 'is_published', 'type' => 'boolean'),
				),
				'fields' => array(
					array('name' => 'verse_text', 'label' => 'Verse Text', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'reference', 'label' => 'Reference', 'type' => 'text', 'required' => TRUE),
					array('name' => 'publish_date', 'label' => 'Publish Date', 'type' => 'date', 'required' => TRUE),
					array('name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox'),
				),
			),
			'news_updates' => array(
				'title' => 'News & Updates',
				'model' => 'news_update_model',
				'slug_field' => 'slug',
				'slug_source' => 'title',
				'columns' => array(
					array('label' => 'Title', 'field' => 'title'),
					array('label' => 'Category', 'field' => 'category'),
					array('label' => 'Publish Date', 'field' => 'publish_date', 'type' => 'date'),
					array('label' => 'Featured', 'field' => 'is_featured', 'type' => 'boolean'),
				),
				'fields' => array(
					array('name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => TRUE),
					array('name' => 'slug', 'label' => 'Slug', 'type' => 'text'),
					array('name' => 'category', 'label' => 'Category', 'type' => 'text'),
					array('name' => 'summary', 'label' => 'Summary', 'type' => 'textarea'),
					array('name' => 'content', 'label' => 'Content', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'image', 'label' => 'Image', 'type' => 'file', 'upload' => array('path' => 'uploads/news/', 'types' => 'gif|jpg|jpeg|png|webp|svg')),
					array('name' => 'publish_date', 'label' => 'Publish Date', 'type' => 'date', 'required' => TRUE),
					array('name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'),
					array('name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => $status_options),
				),
			),
			'galleries' => array(
				'title' => 'Galleries',
				'model' => 'gallery_model',
				'slug_field' => 'slug',
				'slug_source' => 'title',
				'after_save' => 'after_save_galleries',
				'columns' => array(
					array('label' => 'Cover', 'field' => 'cover_image', 'type' => 'image'),
					array('label' => 'Title', 'field' => 'title'),
					array('label' => 'Category', 'field' => 'category'),
					array('label' => 'Date', 'field' => 'album_date', 'type' => 'date'),
				),
				'fields' => array(
					array('name' => 'title', 'label' => 'Album Title', 'type' => 'text', 'required' => TRUE),
					array('name' => 'slug', 'label' => 'Slug', 'type' => 'text'),
					array('name' => 'description', 'label' => 'Description', 'type' => 'textarea'),
					array('name' => 'cover_image', 'label' => 'Cover Image', 'type' => 'file', 'upload' => array('path' => 'uploads/galleries/', 'types' => 'gif|jpg|jpeg|png|webp|svg')),
					array('name' => 'album_date', 'label' => 'Album Date', 'type' => 'date', 'required' => TRUE),
					array('name' => 'category', 'label' => 'Category', 'type' => 'text'),
					array('name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'),
					array('name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => $status_options),
					array('name' => 'gallery_images', 'label' => 'Gallery Images', 'type' => 'file', 'persist' => FALSE, 'upload' => array('path' => 'uploads/galleries/', 'types' => 'gif|jpg|jpeg|png|webp|svg')),
				),
			),
			'users' => array(
				'title' => 'Users',
				'model' => 'user_model',
				'save_method' => 'save_user',
				'list_method' => 'get_all_with_roles',
				'columns' => array(
					array('label' => 'Name', 'field' => 'first_name'),
					array('label' => 'Email', 'field' => 'email'),
					array('label' => 'Role', 'field' => 'role_name'),
					array('label' => 'Active', 'field' => 'is_active', 'type' => 'boolean'),
				),
				'fields' => array(
					array('name' => 'role_id', 'label' => 'Role', 'type' => 'select', 'required' => TRUE, 'options_callback' => 'get_role_options'),
					array('name' => 'first_name', 'label' => 'First Name', 'type' => 'text', 'required' => TRUE),
					array('name' => 'last_name', 'label' => 'Last Name', 'type' => 'text', 'required' => TRUE),
					array('name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => TRUE),
					array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
					array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
					array('name' => 'photo', 'label' => 'Photo', 'type' => 'file', 'upload' => array('path' => 'uploads/users/', 'types' => 'gif|jpg|jpeg|png|webp|svg')),
					array('name' => 'password', 'label' => 'Password', 'type' => 'password'),
					array('name' => 'is_active', 'label' => 'Active', 'type' => 'checkbox'),
				),
			),
			'menu_items' => array(
				'title' => 'Menu Items',
				'model' => 'menu_model',
				'columns' => array(
					array('label' => 'Label', 'field' => 'label'),
					array('label' => 'URL', 'field' => 'url'),
					array('label' => 'Visible', 'field' => 'is_visible', 'type' => 'boolean'),
					array('label' => 'Order', 'field' => 'sort_order'),
				),
				'fields' => array(
					array('name' => 'label', 'label' => 'Label', 'type' => 'text', 'required' => TRUE),
					array('name' => 'url', 'label' => 'URL', 'type' => 'text', 'required' => TRUE),
					array('name' => 'icon', 'label' => 'Icon Class', 'type' => 'text'),
					array('name' => 'target', 'label' => 'Target', 'type' => 'select', 'options' => array('_self' => 'Same tab', '_blank' => 'New tab')),
					array('name' => 'parent_id', 'label' => 'Parent Item', 'type' => 'select', 'options_callback' => 'get_menu_parent_options'),
					array('name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number'),
					array('name' => 'is_visible', 'label' => 'Visible', 'type' => 'checkbox'),
				),
			),
			'service_schedules' => array(
				'title' => 'Service Schedules',
				'model' => 'service_schedule_model',
				'columns' => array(
					array('label' => 'Title', 'field' => 'title'),
					array('label' => 'Day', 'field' => 'day_name'),
					array('label' => 'Time', 'field' => 'time_range'),
					array('label' => 'Visible', 'field' => 'is_visible', 'type' => 'boolean'),
				),
				'fields' => array(
					array('name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => TRUE),
					array('name' => 'day_name', 'label' => 'Day', 'type' => 'text', 'required' => TRUE),
					array('name' => 'time_range', 'label' => 'Time', 'type' => 'text', 'required' => TRUE),
					array('name' => 'description', 'label' => 'Description', 'type' => 'text'),
					array('name' => 'display_order', 'label' => 'Display Order', 'type' => 'number'),
					array('name' => 'is_visible', 'label' => 'Visible', 'type' => 'checkbox'),
				),
			),
			'prayer_requests' => array(
				'title' => 'Prayer Requests',
				'model' => 'prayer_request_model',
				'allow_create' => FALSE,
				'columns' => array(
					array('label' => 'Name', 'field' => 'full_name'),
					array('label' => 'Email', 'field' => 'email'),
					array('label' => 'Private', 'field' => 'is_private', 'type' => 'boolean'),
					array('label' => 'Status', 'field' => 'status', 'type' => 'status'),
				),
				'fields' => array(
					array('name' => 'full_name', 'label' => 'Name', 'type' => 'text', 'required' => TRUE),
					array('name' => 'email', 'label' => 'Email', 'type' => 'email'),
					array('name' => 'mobile_number', 'label' => 'Mobile Number', 'type' => 'text'),
					array('name' => 'request_text', 'label' => 'Request', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => array('pending' => 'Pending', 'approved' => 'Approved', 'closed' => 'Closed')),
					array('name' => 'is_private', 'label' => 'Private', 'type' => 'checkbox'),
				),
			),
			'contact_messages' => array(
				'title' => 'Contact Messages',
				'model' => 'contact_message_model',
				'allow_create' => FALSE,
				'columns' => array(
					array('label' => 'Name', 'field' => 'full_name'),
					array('label' => 'Subject', 'field' => 'subject'),
					array('label' => 'Email', 'field' => 'email'),
					array('label' => 'Status', 'field' => 'status', 'type' => 'status'),
				),
				'fields' => array(
					array('name' => 'full_name', 'label' => 'Name', 'type' => 'text', 'required' => TRUE),
					array('name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => TRUE),
					array('name' => 'mobile_number', 'label' => 'Mobile Number', 'type' => 'text'),
					array('name' => 'subject', 'label' => 'Subject', 'type' => 'text', 'required' => TRUE),
					array('name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'required' => TRUE),
					array('name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => array('new' => 'New', 'read' => 'Read', 'replied' => 'Replied')),
				),
			),
			'newsletter_subscriptions' => array(
				'title' => 'Newsletter Subscriptions',
				'model' => 'newsletter_subscription_model',
				'allow_create' => FALSE,
				'columns' => array(
					array('label' => 'Email', 'field' => 'email'),
					array('label' => 'Status', 'field' => 'status', 'type' => 'status'),
					array('label' => 'Created', 'field' => 'created_at', 'type' => 'datetime'),
				),
				'fields' => array(
					array('name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => TRUE),
					array('name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => array('subscribed' => 'Subscribed', 'unsubscribed' => 'Unsubscribed')),
				),
			),
			'event_registrations' => array(
				'title' => 'Event Registrations',
				'model' => 'event_registration_model',
				'allow_create' => FALSE,
				'allow_edit' => FALSE,
				'list_callback' => 'list_event_registrations',
				'columns' => array(
					array('label' => 'Event', 'field' => 'event_title'),
					array('label' => 'Full Name', 'field' => 'full_name'),
					array('label' => 'Email', 'field' => 'email'),
					array('label' => 'Created', 'field' => 'created_at', 'type' => 'datetime'),
				),
				'fields' => array(),
			),
			'settings' => array(
				'title' => 'Website Settings',
				'model' => 'setting_model',
				'columns' => array(
					array('label' => 'Key', 'field' => 'setting_key'),
					array('label' => 'Label', 'field' => 'label'),
					array('label' => 'Group', 'field' => 'setting_group'),
				),
				'fields' => array(
					array('name' => 'setting_key', 'label' => 'Setting Key', 'type' => 'text', 'required' => TRUE),
					array('name' => 'label', 'label' => 'Label', 'type' => 'text', 'required' => TRUE),
					array('name' => 'setting_group', 'label' => 'Group', 'type' => 'text', 'required' => TRUE),
					array('name' => 'setting_value', 'label' => 'Setting Value', 'type' => 'textarea'),
				),
			),
			'activity_logs' => array(
				'title' => 'Activity Logs',
				'model' => 'activity_log_model',
				'allow_create' => FALSE,
				'allow_edit' => FALSE,
				'allow_delete' => FALSE,
				'list_callback' => 'list_activity_logs',
				'columns' => array(
					array('label' => 'User', 'field' => 'user_name'),
					array('label' => 'Action', 'field' => 'action'),
					array('label' => 'Module', 'field' => 'module'),
					array('label' => 'Created', 'field' => 'created_at', 'type' => 'datetime'),
				),
				'fields' => array(),
			),
		);
	}
}