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
		$this->load->library(array('input'));
	}

	/** 
	 * Sends out reminders for appointments (that are tomorrow)
	 */
	public function appointments()
	{
		if (!$this->input->is_cli_request())
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
				reset_language(L::Dutch);
				
				$participant = $this->participationModel->get_participant_by_participation($participation->id);
				$experiment = $this->participationModel->get_experiment_by_participation($participation->id);

				$message_args = array(
						"participant" => $participant,
						"participation" => $participation,
						"experiment" => $experiment
					)
				$message = email_replace('mail/reminder', $message_args);
		
				$this->email->prepare();
				$this->email->to($participant->email);
				$this->email->to_name(parent_name($participant));
				$this->email->subject('Herinnering deelname');
				$this->email->ending("Tot morgen", BABYLAB_TEAM);
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
		if (!$this->input->is_cli_request())
		{
			echo "This script can only be accessed via the command line" . PHP_EOL;
			return;
		}

		$users = $this->userModel->get_all_callers();
		foreach ($users as $user)
		{
			reset_language(user_language($user));

			$this->email->prepare(True);
			$this->email->to($user->email);
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
				$this->email->to_name($user->username);
				$this->email->$message(lang('rem_body') . br(1) . ul($call_messages));
				$this->email->send();
				// DEBUG: echo $this->email->print_debugger();
			}
		}
	}
}

/* End of file reminder.php */
/* Location: ./application/controllers/cron/reminder.php */