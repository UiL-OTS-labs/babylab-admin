<?php
class Surveyinvite extends CI_Controller
{
	// cron job (crontab -e):
	// 0 9 *   *   *     php /var/www/babylab/index.php cron/surveyinvite invite
	// 0 9 *   *   *     php /var/www/babylab/index.php cron/surveyinvite reminder
	// will send call test invitation/reminder mails out every day at 9 AM.

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('input'));
		$this->load->model('surveyModel');
	}

	/**
	 * Sends out invitations for surveys
	 */
	public function invite()
	{
		if (!is_cli())
		{
			echo "This script can only be accessed via the command line" . PHP_EOL;
			return;
		}

		// Set the language to Dutch (TODO: set to language of participant?)
		reset_language(L::Dutch);

		// Get all age-based testsurveys (definitions of when tests should be sent)
		$testsurveys = $this->testSurveyModel->get_testsurveys_by_when(TestWhenSent::Months);
		foreach ($testsurveys as $testsurvey)
		{
			// Find all participants of the correct age
			$participants = $this->participantModel->get_participants_of_age($testsurvey->whennr);
			$participants += $this->participantModel->get_participants_of_age($testsurvey->whennr - 1);
			foreach ($participants as $participant)
			{
				// Check if the participant has a participation, if not, continue
				$participations = $this->participationModel->get_participations_by_participant($participant->id, TRUE);
				if (empty($participations))
				{
					continue;
				}

				// Check if invitation already exists
				$testinvite = $this->testInviteModel->get_testinvite_by_testsurvey_participant($testsurvey->id, $participant->id);
					
				// If not, create the invitation with a certain chance (to help spread participant's age)
				if (empty($testinvite))
				{
					$rand = mt_rand(1, 100);
					$probability = $this->probability($participant, $testsurvey->whennr);
					if ($rand <= $probability)
					{
						$this->send_testinvite($testsurvey, $participant);
					}
				}
			}
		}
	}

	/**
	 * Sends out reminders for surveys
	 */
	public function reminder()
	{
		if (!is_cli())
		{
			echo "This script can only be accessed via the command line" . PHP_EOL;
			return;
		}

		// Set the language to Dutch (TODO: set to language of participant?)
		reset_language(L::Dutch);

		// Get all testinvites that have not yet been reminded
		$testinvites = $this->testInviteModel->get_not_reminded_testinvites(); 
		foreach ($testinvites as $testinvite)
		{
			$date_sent = new DateTime($testinvite->datesent);
			$diff_days = $date_sent->diff(new DateTime())->days;

			// Check with LimeSurvey whether the survey has actually been completed
			$testsurvey = $this->testInviteModel->get_testsurvey_by_testinvite($testinvite);
			$result = $this->surveyModel->get_result_by_token($testsurvey->limesurvey_id, $testinvite->token);
			if ($result) 
			{
				// If there is actually a result row, set the survey to completed
				$this->testInviteModel->set_completed($testinvite->id, $result->submitdate);
				continue; 
			}

			// If no reminder has yet been sent and it's been some days, send a reminder e-mail
			if ($diff_days >= SEND_REMINDER_AFTER_DAYS)
			{
				$test = $this->testInviteModel->get_test_by_testinvite($testinvite);
				$template = $this->testTemplateModel->get_testtemplate_by_test($test->id, L::Dutch);
				$participant = $this->testInviteModel->get_participant_by_testinvite($testinvite);

				$message = email_replace($template->template . '_reminder', $participant, NULL, NULL, $testinvite);

				$this->email->clear();
				$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
				$this->email->to(in_development() ? TO_EMAIL_DEV_MODE : $participant->email);
				$this->email->subject('Babylab Utrecht: Herinnering uitnodiging voor vragenlijst');
				$this->email->message($message);
				$this->email->send();

				$this->testInviteModel->set_reminded($testinvite->id);
			}
		}
	}

	/**
	 * Checks if surveys have been completed
	 */
	public function check_completed()
	{
		if (!is_cli())
		{
			echo "This script can only be accessed via the command line" . PHP_EOL;
			return;
		}

		// Set the language to Dutch (TODO: set to language of participant?)
		reset_language(L::Dutch);

		// Get all testinvites that have not yet been completed
		$testinvites = $this->testInviteModel->get_not_completed_testinvites(); 
		foreach ($testinvites as $testinvite)
		{
			$testsurvey = $this->testInviteModel->get_testsurvey_by_testinvite($testinvite);
			// If the survey still exists in LimeSurvey...
			if ($this->surveyModel->get_survey_by_id($testsurvey->limesurvey_id))
			{
				// Check with LimeSurvey whether the survey has actually been completed
				$result = $this->surveyModel->get_result_by_token($testsurvey->limesurvey_id, $testinvite->token);
				if ($result) 
				{
					// If there is actually a result row, set the survey to completed
					$this->testInviteModel->set_completed($testinvite->id, $result->submitdate);
				}
			}
		}
	}

	/**
	 * Returns the probability for sending the survey: 
	 *  m;d 	percentage
	 * 17;28	 10%
	 * 18;0		 20%
	 * 18;7		 30%
	 * 18;14	 40%
	 * 18;21	100%
	 */
	private function probability($participant, $m)
	{
		$age = explode(';', age_in_months_and_days($participant->dateofbirth));
		$month = $age[0];
		$day = $age[1];

		if ($month < $m)
		{
			return $day < 28 ? 0 : 10;
		}
		else 
		{
			return $day < 7 ? 20 : ($day < 14 ? 30 : ($day < 21 ? 40 : 100)); 
		}

	}

	/**
	 * Send the invitation to the participant
	 */
	private function send_testinvite($testsurvey, $participant)
	{
		// Create the testinvite
		$testinvite = $this->testInviteModel->create_testinvite($testsurvey->id, $participant->id);

		// Create the token in LimeSurvey (if we're on production)
		if (!SURVEY_DEV_MODE)
		{
			$this->load->model('surveyModel');
			$this->surveyModel->create_token($participant, $testsurvey->limesurvey_id, $testinvite->token);
		}

		// Email to participant
		email_testinvite($participant, $testinvite, TRUE);
	}
}

/* End of file Surveyinvite.php */
/* Location: ./application/controllers/cron/Surveyinvite.php */