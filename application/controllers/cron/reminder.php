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
	 * Sends out reminders for appointments.
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
			if ($appointment > time() && $appointment < strtotime('+1 day'))
			{
				reset_language(L::Dutch);

				$participant = $this->participationModel->get_participant_by_participation($participation->id);
				$template = file_get_contents('mail/reminder.html');
				$message = email_replace($template, $participant, $participation);

				$this->email->clear();
				$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
				$this->email->to(EMAIL_DEV_MODE ? TO_EMAIL_OVERRIDE : $participant->email);
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
		if (!$this->input->is_cli_request())
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
			$this->email->to(EMAIL_DEV_MODE ? TO_EMAIL_OVERRIDE : $user->email);
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

			if (!empty($call_messages))
			{
				$message = sprintf(lang('mail_heading'), $user->username);
				$message .= br(2);
				$message .= lang('rem_body');
				$message .= br(1);
				$message .= ul($call_messages);
				$message .= lang('mail_ending');
				$message .= br(2);
				$message .= lang('mail_disclaimer');

				$this->email->message($message);
				$this->email->send();
				// DEBUG: echo $this->email->print_debugger();
			}
		}
	}
}

/* End of file reminder.php */
/* Location: ./application/controllers/cron/reminder.php */