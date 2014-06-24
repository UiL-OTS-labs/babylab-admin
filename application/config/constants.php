<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/** Roles */
class UserRole {
	const Admin 		= 'admin';
	const Leader 		= 'leader';
	const Caller 		= 'caller';
	const System 		= 'system';
}

/** Gender */
class Gender {
	const Male 			= 'm';
	const Female 		= 'f';
	const Both 			= 'mf';
	const None 			= 'none';
}

/** Call status */
class CallStatus {
	const CallStarted	= 'call_started';
	const NoReply 		= 'no_reply';
	const Voicemail 	= 'voicemail';
	const Email 		= 'email';
	const Confirmed 	= 'confirmed';
	const Cancelled 	= 'cancelled';
}

/** Participation status */
class ParticipationStatus {
	const Unconfirmed 	= 'unconfirmed';
	const Confirmed 	= 'confirmed';
	const Rescheduled 	= 'rescheduled';
	const Cancelled 	= 'cancelled';
	const Completed 	= 'completed';
	const NoShow 		= 'no_show';
}

/** Relations */
class RelationType {
	const Prerequisite 	= 'prerequisite';
	const Excludes 		= 'excludes';
}

/** When to sent tests */
class TestWhenSent {
	const Participation	= 'participation';
	const Months		= 'months';
}

/** Languages */
class L {
	const English 		= 'english';
	const Dutch 		= 'dutch';
}

/** Number of weeks to look ahead */
define('WEEKS_AHEAD', 2);

/** Duration of instructions: 20 minutes */
define('INSTRUCTION_DURATION', 20);

/** Minimum NCDI percentile */
define('NCDI_MINIMUM_PERCENTILE', 20);

/* End of file constants.php */
/* Location: ./application/config/constants.php */