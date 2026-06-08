<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'site/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['about-us'] = 'site/about';
$route['pastors'] = 'site/pastors';
$route['ministries'] = 'site/ministries';
$route['sermons'] = 'site/sermons';
$route['sermons/(:any)'] = 'site/sermon/$1';
$route['events'] = 'site/events';
$route['events/(:any)'] = 'site/event/$1';
$route['announcements'] = 'site/announcements';
$route['announcements/(:any)'] = 'site/announcement/$1';
$route['news-updates'] = 'site/news';
$route['news-updates/(:any)'] = 'site/news_item/$1';
$route['gallery'] = 'site/gallery';
$route['gallery/(:any)'] = 'site/gallery_album/$1';
$route['contact'] = 'site/contact';
$route['search'] = 'site/search';
$route['prayer-request'] = 'site/prayer_request';
$route['newsletter-subscribe'] = 'site/newsletter_subscribe';
$route['event-register/(:num)'] = 'site/event_register/$1';

$route['admin'] = 'admin/dashboard';
$route['admin/login'] = 'admin/auth/login';
$route['admin/logout'] = 'admin/auth/logout';
$route['admin/forgot-password'] = 'admin/auth/forgot_password';
$route['admin/reset-password/(:any)'] = 'admin/auth/reset_password/$1';
$route['admin/change-password'] = 'admin/auth/change_password';
$route['admin/content'] = 'admin/content/index';
$route['admin/content/(:any)'] = 'admin/content/index/$1';
$route['admin/content/(:any)/view/(:num)'] = 'admin/content/view/$1/$2';
$route['admin/content/(:any)/create'] = 'admin/content/create/$1';
$route['admin/content/(:any)/edit/(:num)'] = 'admin/content/edit/$1/$2';
$route['admin/content/(:any)/delete/(:num)'] = 'admin/content/delete/$1/$2';
