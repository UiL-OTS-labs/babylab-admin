<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 | -------------------------------------------------------------------------
 | Email
 | -------------------------------------------------------------------------
 | This file lets you define parameters for sending emails.
 | Please see the user guide for info:
 |
 |	http://codeigniter.com/user_guide/libraries/email.html
 |
 */
$config['mailtype'] 	= 'html';
$config['charset'] 		= 'utf-8';
$config['newline'] 		= '\r\n';
$config['crlf'] 		= '\r\n';

/** Sender e-mail */
define('FROM_EMAIL',		'');
/** Sender name */
define('FROM_EMAIL_NAME',	'');
/** In-interface override for recipient email */
define('TO_EMAIL_OVERRIDE',	'');
/** Development mode recipient address (requires environment == development) */
define('TO_EMAIL_DEV_MODE',	'');
/** Lab e-mail */
define('LAB_EMAIL',			'');
/** Babylab Manager / Team */ 
define('BABYLAB_MANAGER',	'');
define('BABYLAB_MANAGER_EMAIL',	'');
define('BABYLAB_MANAGER_PHONE',	'');
define('BABYLAB_TEAM',		'');

/* End of file email.php */
/* Location: ./application/config/email.php */
