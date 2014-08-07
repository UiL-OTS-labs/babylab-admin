<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 |	http://codeigniter.com/user_guide/general/routing.html
 |
 | -------------------------------------------------------------------------
 | RESERVED ROUTES
 | -------------------------------------------------------------------------
 |
 | There area two reserved routes:
 |
 |	$route['default_controller'] = 'welcome';
 |
 | This route indicates which controller class should be loaded if the
 | URI contains no data. In the above example, the "welcome" class
 | would be loaded.
 |
 |	$route['404_override'] = 'errors/page_missing';
 |
 | This route will tell the Router what URI segments to use if those provided
 | in the URL cannot be matched to a valid route.
 |
 */

$route['default_controller'] 	= 'login';
$route['404_override'] 			= '';

$route['inloggen'] 				= 'login/index/dutch';
$route['login'] 				= 'login/index/english';

$route['signup'] 				= 'participant/register';
$route['aanmelden'] 			= 'participant/register/dutch';
$route['signup_submit']			= 'participant/register_submit';
$route['aanmelden_versturen']	= 'participant/register_submit/dutch';
$route['signup_finished']		= 'participant/register_finish';
$route['aanmelden_afgerond']	= 'participant/register_finish/dutch';

$route['deregister'] 			= 'participant/deregister';
$route['afmelden'] 				= 'participant/deregister/dutch';
$route['deregister_submit']		= 'participant/deregister_submit';
$route['afmelden_versturen']	= 'participant/deregister_submit/dutch';
$route['deregister_finished']	= 'participant/deregister_finish';
$route['afmelden_afgerond']		= 'participant/deregister_finish/dutch';

$route['forgot_password'] 		= 'user/forgot_password';
$route['wachtwoord_vergeten'] 	= 'user/forgot_password/dutch';
$route['register'] 				= 'user/register';
$route['registreren']			= 'user/register/dutch';
$route['resetpw/(:any)'] 		= 'user/reset_password/$1';

$route['c/(:any)/(:any)/(:any)']= 'charts/chart/$3/$1/$2';
$route['c/(:any)/(:any)'] 		= 'charts/chart/$2/$1';
$route['c/(:any)'] 				= 'charts/chart/home/$1';
$route['f/(:any)/(:any)'] 		= 'charts/chart/fill_scores/$1/$2';

$route['ncdi_checker'] 			= 'charts/ncdichecker';

/* End of file routes.php */
/* Location: ./application/config/routes.php */