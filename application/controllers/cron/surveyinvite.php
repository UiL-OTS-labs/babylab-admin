<?php
class SurveyInvite extends CI_Controller
{
	// cron job (crontab -e):
	// 0 11 *   *   1     php /var/www/babylab/index.php cron/surveyinvite invite
	// will send call test invitation mails out every monday at 11 AM.

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('input'));
	}

	/**
	 * Sends out invitations for surveys
	 */
	public function invite()
	{
		if (!$this->input->is_cli_request())
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
			foreach ($participants AS $participant)
			{
				// Check if invitation already exists
				$testinvite = $this->testInviteModel->get_testinvite_by_testsurvey_participant($testsurvey->id, $participant->id);
					
				// If not, create the invitation
				if (empty($testinvite))
				{
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
		}
	}
}

/* End of file surveyinvite.php */
/* Location: ./application/controllers/cron/surveyinvite.php */