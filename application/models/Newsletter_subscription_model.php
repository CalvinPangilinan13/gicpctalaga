<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter_subscription_model extends MY_Model
{
	protected $table = 'newsletter_subscriptions';
	protected $order_by = 'created_at DESC';
}