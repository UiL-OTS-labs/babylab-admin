<?php
class Reminder extends CI_Controller
{
	// cron job (crontab -e):
	// 0 11 *   *   1     php /var/www/babylab/index.php cron/reminder callers
	// will send call reminder mails out every monday at 11 AM.
	// 0  8 *   *   *     php /var/www/babylab/index.php cron/reminder appointments
	// will send appointment reminder mails out every day at 8 AM.

	public function __construct()
	{
		parent::__construct();
	}

	/** 
	 * Sends out reminders for appointments (that are tomorrow)
	 */
	public function appointments()
	{
		if (!is_cli())
		{
			echo "This script can only be accessed via the command line" . PHP_EOL;
			return;
		}

		$participations = $this->participationModel->get_confirmed_participations();
		foreach ($participations as $participation)
		{
			$appointment = strtotime($participation->appointment); 
			if ($appointment > strtotime('tomorrow') && $appointment <= strtotime('tomorrow + 1 day')) 
			{					
				reset_language(L::DUTCH);
				
				$participant = $this->participationModel->get_participant_by_participation($participation->id);
				$experiment = $this->participationModel->get_experiment_by_participation($participation->id);
				$message = email_replace('mail/reminder', $participant, $participation, $experiment);
		
				$this->email->clear();
				$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
				$this->email->to(in_development() ? TO_EMAIL_DEV_MODE : $participant->email);
				$this->email->subject('Babylab Utrecht: Herinnering deelname');
				$this->email->message($message);
				$this->email->send();
				// DEBUG: $this->email->print_debugger();
			}
		}
	}
	
	/** 
	 * Sends out reminders for callers. 
	 */
	public function callers()
	{
		if (!is_cli())
		{
			echo "This script can only be accessed via the command line" . PHP_EOL;
			return;
		}

		$users = $this->userModel->get_all_callers();
		foreach ($users as $user)
		{
			reset_language(user_language($user));

			$this->email->clear();
			$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
			$this->email->to(in_development() ? TO_EMAIL_DEV_MODE : $user->email);
			$this->email->subject(lang('rem_subject'));

			$call_messages = array();
			$experiments = $this->callerModel->get_experiments_by_caller($user->id);
			foreach ($experiments as $experiment)
			{
				if ($experiment->archived != 1)
				{
					$count = count($this->participantModel->find_participants($experiment));
					if ($count > 0) array_push($call_messages, sprintf(lang('rem_exp_call'), $experiment->name, $count));
				}
			}

			if ($call_messages)
			{
				$message = sprintf(lang('mail_heading'), $user->username);
				$message .= '<br /><br />';
				$message .= lang('rem_body');
				$message .= '<br />';
				$message .= ul($call_messages);
				$message .= lang('mail_ending');
				$message .= '<br /><br />';
				$message .= lang('mail_disclaimer');

				$this->email->message($message);
				$this->email->send();
				// DEBUG: echo $this->email->print_debugger();
			}
		}
	}
}

/* End of file Reminder.php */
/* Location: ./application/controllers/cron/Reminder.php */