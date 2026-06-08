<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_image_model extends MY_Model
{
	protected $table = 'gallery_images';
	protected $order_by = 'display_order ASC, id ASC';

	public function get_by_gallery($gallery_id)
	{
		return $this->get_by(array('gallery_id' => $gallery_id));
	}
}