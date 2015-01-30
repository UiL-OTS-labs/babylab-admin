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
	const Combination	= 'combination';
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

/** Deactivation reasons */
class DeactivateReason {
	const NewParticipant = 'new';
	const DuringCall	= 'call';
	const AfterExp		= 'exp';
	const Manual		= 'manual';
	const SelfService	= 'selfservice';
}

/** Exclusion reasons */
class ExcludedReason {
	const Crying 				= 'crying';
	const FussyOrRestless		= 'fussy';
	const ParentalInterference	= 'parent';
	const TechnicalProblems		= 'tech_problems';
	const Interrupted			= 'interrupted';
	const Other					= 'other';
}

/** Number of weeks to look ahead */
define('WEEKS_AHEAD', 2);

/** Duration of instructions: 20 minutes */
define('INSTRUCTION_DURATION', 20);

/** After how many calls to send a request for participation */
define('SEND_REQUEST_AFTER_CALLS', 2);

/** Minimum NCDI percentile: 10th percentile */
define('NCDI_MINIMUM_PERCENTILE', 10);
/** NCDI language age difference: 4 months */
define('NCDI_LANGUAGE_AGE_DIFF', 4);

/** Babylab Manager / Team */ 
define('BABYLAB_MANAGER', 'Maartje de Klerk');
define('BABYLAB_MANAGER_EMAIL', 'babylabutrecht@uu.nl');
define('BABYLAB_MANAGER_PHONE', '06-15084044');
define('BABYLAB_TEAM', 'Het Babylab team van de Universiteit Utrecht');

/** Availability */
define('AVAILABILITY_DEFAULT_TIMES', '["08:30", "18:00"]');

/* End of file constants.php */
/* Location: ./application/config/constants.php */