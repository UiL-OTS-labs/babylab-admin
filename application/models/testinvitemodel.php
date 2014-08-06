<?php
class TestInviteModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all testinvites as an array */
	public function get_all_testinvites()
	{
		return $this->db->get('testinvite')->result();
	}

	/** Adds a testinvite to the DB */
	public function add_testinvite($testinvite)
	{
		$this->db->insert('testinvite', $testinvite);
		return $this->db->insert_id();
	}

	/** Updates the testinvite specified by the id with the details of the testinvite */
	public function update_testinvite($testinvite_id, $testinvite)
	{
		$this->db->where('id', $testinvite_id);
		$this->db->update('testinvite', $testinvite);
	}

	/** Deletes a testinvite from the DB */
	public function delete_testinvite($testinvite_id)
	{
		$testinvite = $this->get_testinvite_by_id($testinvite_id);
		$testsurvey = $this->get_testsurvey_by_testinvite($testinvite);

		// Delete results from LimeSurvey database (if we're on production)
		if (!SURVEY_DEV_MODE)
		{
			$this->load->model('surveyModel');
			$this->surveyModel->invalidate_token($testsurvey->limesurvey_id, $testinvite->token);
		}

		// Delete references to scores
		$this->db->delete('score', array('testinvite_id' => $testinvite_id));

		$this->db->delete('testinvite', array('id' => $testinvite_id));
	}

	/** Returns the testinvite for an id */
	public function get_testinvite_by_id($testinvite_id)
	{
		return $this->db->get_where('testinvite', array('id' => $testinvite_id))->row();
	}

	/** Returns the testinvite for a testsurvey and a participant (unique key) */
	public function get_testinvite_by_testsurvey_participant($testsurvey_id, $participant_id)
	{
		return $this->db->get_where('testinvite', array('testsurvey_id' => $testsurvey_id, 'participant_id' => $participant_id))->row();
	}

	/** Returns the testinvite for a token (unique key) */
	public function get_testinvite_by_token($token)
	{
		return $this->db->get_where('testinvite', array('token' => $token))->row();
	}

	/** Returns the testinvite for a token (unique key) */
	public function set_completed($testinvite_id)
	{
		$this->db->set('datecompleted', input_datetime());
		$this->db->where('id', $testinvite_id);
		$this->db->update('testinvite');
	}

	/////////////////////////
	// Test Surveys
	/////////////////////////

	/** Returns the invites for a survey */
	public function get_testinvites_by_testsurvey($testsurvey_id)
	{
		$this->db->where('testsurvey_id', $testsurvey_id);
		return $this->db->get('testinvite')->result();
	}

	/** Returns the survey for an invitation */
	public function get_testsurvey_by_testinvite($testinvite)
	{
		return $this->db->get_where('testsurvey', array('id' => $testinvite->testsurvey_id))->row();
	}

	/////////////////////////
	// Tests
	/////////////////////////

	/** Returns the invites for a test */
	public function get_testinvites_by_test($test_id)
	{
		$this->db->select('testinvite.*');
		$this->db->where('test_id', $test_id);
		$this->db->join('testsurvey', 'testsurvey.id = testinvite.testsurvey_id');
		return $this->db->get('testinvite')->result();
	}

	/** Returns the test for an invitation */
	public function get_test_by_testinvite($testinvite)
	{
		$this->db->select('test.*');
		$this->db->where('testsurvey.id', $testinvite->testsurvey_id);
		$this->db->join('testsurvey', 'testsurvey.test_id = test.id');
		return $this->db->get('test')->row();
	}

	/////////////////////////
	// Participants
	/////////////////////////

	/** Returns the invites for a participant */
	public function get_testinvites_by_participant($participant_id)
	{
		$this->db->where('participant_id', $participant_id);
		return $this->db->get('testinvite')->result();
	}

	/** Returns the participant for an invitation */
	public function get_participant_by_testinvite($testinvite)
	{
		return $this->db->get_where('participant', array('id' => $testinvite->participant_id))->row();
	}

	/** Returns the previous testinvites */
	public function get_previous_testinvites($participant, $testinvite)
	{
		$test = $this->get_test_by_testinvite($testinvite);
		$testsurvey = $this->get_testsurvey_by_testinvite($testinvite);

		$this->db->select('testinvite.*');
		$this->db->from('testinvite');
		$this->db->join('testsurvey', 'testsurvey.id = testinvite.testsurvey_id');
		$this->db->where('testinvite.participant_id', $participant->id);
		$this->db->where('testinvite.id !=', $testinvite->id);
		$this->db->where('testsurvey.whennr <', $testsurvey->whennr);
		$this->db->where('testsurvey.test_id', $test->id);
		$this->db->order_by('testsurvey.whennr asc');
		return $this->db->get()->result();
	}

	/////////////////////////
	// Create invitations
	/////////////////////////

	/** Checks and creates test invitations */
	public function create_testinvites_by_participation($participant)
	{
		$participations = $this->participationModel->get_participations_by_participant($participant->id, TRUE);
		$surveys = $this->testSurveyModel->get_testsurveys_by_when(TestWhenSent::Participation, count($participations));

		$result = array();
		foreach ($surveys AS $survey)
		{
			$testinvite = $this->create_testinvite($survey->id, $participant->id);
			array_push($result, $testinvite);

			// Create the token in LimeSurvey (if we're on production)
			if (!SURVEY_DEV_MODE)
			{
				$this->load->model('surveyModel');
				$this->surveyModel->create_token($participant, $survey->limesurvey_id, $testinvite->token);
			}
		}

		return $result;
	}

	/** Create the test invitation (with token) using the survey and participant id */
	public function create_testinvite($testsurvey_id, $participant_id)
	{
		$unique_token = FALSE;
		while (!$unique_token)
		{
			$token = bin2hex(openssl_random_pseudo_bytes(8));
			$testinvite = $this->get_testinvite_by_token($token);
			$unique_token = empty($testinvite);
		}

		$testinvite = array(
			'testsurvey_id' 	=> $testsurvey_id, 
			'participant_id'	=> $participant_id,
			'token'				=> $token);

		$testinvite_id = $this->add_testinvite($testinvite);
		return $this->get_testinvite_by_id($testinvite_id);
	}
}