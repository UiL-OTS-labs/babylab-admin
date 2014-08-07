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
define('FROM_EMAIL',		'babylabutrecht@uu.nl');
/** Sender name */
define('FROM_EMAIL_NAME',	'Babylab Utrecht');
/** Development override for recipient */
define('TO_EMAIL_OVERRIDE',	'M.H.vanderKlis@uu.nl');
/** Development mode on or off */
define('EMAIL_DEV_MODE',	TRUE);

/* End of file email.php */
/* Location: ./application/config/email.php */
