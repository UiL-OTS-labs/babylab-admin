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
/** Development override for recipient */
define('TO_EMAIL_OVERRIDE',	'');
/** Development mode on or off */
define('EMAIL_DEV_MODE',	TRUE);
/** Lab e-mail */
define('LAB_EMAIL',			'');
/** Babylab Manager / Team */ 
define('BABYLAB_MANAGER',	'');
define('BABYLAB_MANAGER_EMAIL',	'');
define('BABYLAB_MANAGER_PHONE',	'');
define('BABYLAB_TEAM',		'');

/* End of file email.php */
/* Location: ./application/config/email.php */
