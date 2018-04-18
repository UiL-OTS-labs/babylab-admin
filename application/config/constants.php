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
	const ADMIN 		= 'admin';
	const LEADER 		= 'leader';			// Has access to both leader and caller interface
	const RESEARCHER	= 'researcher';		// Only has access to leader interface
	const CALLER 		= 'caller';
	const SYSTEM 		= 'system';
}

/** Gender */
class Gender {
	const MALE 			= 'm';
	const FEMALE 		= 'f';
	const BOTH 			= 'mf';
	const NONE 			= 'none';
}

/** Call status */
class CallStatus {
	const CALL_STARTED	= 'call_started';
	const NO_REPLY 		= 'no_reply';
	const CALL_BACK 	= 'call_back';
	const VOICEMAIL 	= 'voicemail';
	const EMAIL 		= 'email';
	const CONFIRMED 	= 'confirmed';
	const CANCELLED 	= 'cancelled';
}

/** Participation status */
class ParticipationStatus {
	const UNCONFIRMED 	= 'unconfirmed';
	const CONFIRMED 	= 'confirmed';
	const RESCHEDULED 	= 'rescheduled';
	const CANCELLED 	= 'cancelled';
	const COMPLETED 	= 'completed';
	const NO_SHOW 		= 'no_show';
}

/** Relations */
class RelationType {
	const PREREQUISITE 	= 'prerequisite';
	const EXCLUDES 		= 'excludes';
	const COMBINATION	= 'combination';
}

/** When to sent tests */
class TestWhenSent {
	const PARTICIPATION	= 'participation';
	const MONTHS		= 'months';
	const MANUAL		= 'manual';
}

/** Languages */
class L {
	const ENGLISH 		= 'english';
	const DUTCH 		= 'dutch';
}

/** Deactivation reasons */
class DeactivateReason {
	const NEW_PARTICIPANT   = 'new';
	const DURING_CALL	    = 'call';
	const AFTER_EXP		    = 'exp';
	const MANUAL		    = 'manual';
	const SELF_SERVICE	    = 'selfservice';
	const FROM_SURVEY	    = 'survey';
}

/** Exclusion reasons */
class ExcludedReason {
	const CRYING 				= 'crying';
	const FUSSY_OR_RESTLESS		= 'fussy';
	const PARENTAL_INTERFERENCE	= 'parent';
	const TECHNICAL_PROBLEMS	= 'tech_problems';
	const INTERRUPTED			= 'interrupted';
	const OTHER					= 'other';
}

/** Number of weeks to look ahead */
define('WEEKS_AHEAD', 2);

/** Default duration of instructions: 20 minutes */
define('INSTRUCTION_DURATION', 20);

/** After how many calls to send a request for participation */
define('SEND_REQUEST_AFTER_CALLS', 2);

/** After how many days to send a reminder for a survey */
define('SEND_REMINDER_AFTER_DAYS', 7);

/** Minimum NCDI percentile: 10th percentile */
define('NCDI_MINIMUM_PERCENTILE', 10);
/** NCDI language age difference: 4 months */
define('NCDI_LANGUAGE_AGE_DIFF', 4);

/** Availability */
define('AVAILABILITY_DEFAULT_TIMES', '["08:30", "18:00"]');

/* End of file constants.php */
/* Location: ./application/config/constants.php */