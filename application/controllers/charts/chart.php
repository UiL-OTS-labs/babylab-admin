<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chart extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		reset_language(L::Dutch);

		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}

	/////////////////////////
	// Pages: home, cp (compare percentiles), vs (versus scores), more
	/////////////////////////

	/**
	 * Home page
	 * @param $test_code
	 * @param $token
	 */
	public function home($test_code, $token = NULL)
	{
		$test = $this->get_test_or_die($test_code);
		$data['page_title'] = $test->name;
		$data = $this->set_test_data($data, $test);
		$data = $this->set_token_data($data, $token);

		$this->load->view($this->view_code($test, 'header'), $data);
		$this->load->view($this->view_code($test, 'home'), $data);
		$this->load->view('templates/footer');
	}

	/** Score page */
	public function sc($test_code, $token = NULL)
	{
		$test = $this->get_test_or_die($test_code);
		$data['page_title'] = $test->name . ' - ' . lang('scores');
		$data = $this->set_test_data($data, $test);
		$data = $this->set_token_data($data, $token);

		if ($data['valid_token'] && substr($test->code, 0, 4) === 'ncdi') // TODO: magic string
		{
			// Calculate the scores
			$testinvite = $data['testinvite'];
			$scores = create_ncdi_score_array($test, $testinvite);
			$data['ncdi_table'] = create_ncdi_table($scores);

			// Find any existing previous scores for this participant
			$participant = $this->participantModel->get_participant_by_id($data['participant_id']);
			$prev_testinvites = $this->testInviteModel->get_previous_testinvites($participant, $testinvite);
			$data['has_prev_results'] = FALSE;

			// Add some comments on the results
			$comments = $this->add_comments_to_score($scores, $participant);
			$data['ncdi_text'] = ul($comments);

			// Loop over the scores and add them to the page (without comments)
			$prev_tables = array();
			$prev_descs = array();
			foreach ($prev_testinvites AS $prev_testinvite)
			{
				$scores = create_ncdi_score_array($test, $prev_testinvite);
				$date = output_date($prev_testinvite->datecompleted);
				$gender = gender_child($participant->gender);
				$age = age_in_months($participant, $prev_testinvite->datecompleted);
				$data['has_prev_results'] = TRUE;
				array_push($prev_tables, create_ncdi_table($scores));
				array_push($prev_descs, sprintf('Resultaten van %s. Uw %s was toen <strong>%s maanden</strong> oud.', $date, $gender, $age));
			}

			$data['ncdi_prev_tables'] = $prev_tables;
			$data['ncdi_prev_descs'] = $prev_descs;
		}

		$this->load->view($this->view_code($test, 'header'), $data);
		$this->load->view($this->view_code($test, 'scores'), $data);
		$this->load->view('templates/footer');
	}

	/** Percentile page */
	public function cp($test_code, $token = NULL)
	{
		$test = $this->get_test_or_die($test_code);
		$data['page_title'] = $test->name . ' - ' . lang('percentiles');
		$data = $this->set_test_data($data, $test);
		$data = $this->set_token_data($data, $token);

		$this->load->view($this->view_code($test, 'header'), $data);
		$this->load->view($this->view_code($test, 'compare_percentiles'), $data);
		$this->load->view('templates/footer');
	}

	/** VS score page */
	public function vs($test_code, $token = NULL)
	{
		$test = $this->get_test_or_die($test_code);
		$data['page_title'] = $test->name . ' - ' . 'Alle scores';
		$data = $this->set_test_data($data, $test);
		$data = $this->set_token_data($data, $token);

		$this->load->view($this->view_code($test, 'header'), $data);
		$this->load->view($this->view_code($test, 'vs_scores'), $data);
		$this->load->view('templates/footer');
	}

	/** More information page */
	public function more($test_code, $token = NULL)
	{
		$test = $this->get_test_or_die($test_code);
		$data['page_title'] = $test->name . ' - ' . 'Meer informatie';
		$data = $this->set_test_data($data, $test);
		$data = $this->set_token_data($data, $token);

		$this->load->view($this->view_code($test, 'header'), $data);
		$this->load->view($this->view_code($test, 'more'), $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Finds the appropriate test-invite for the token and fills the scores.
	 * @param string $test_code
	 * @param string $token
	 */
	public function fill_scores($test_code, $token)
	{
		if (!SURVEY_DEV_MODE)
		{
			$test = $this->testModel->get_test_by_code($test_code);
			$testinvite = $this->testInviteModel->get_testinvite_by_token($token);
			$testsurvey = $this->testInviteModel->get_testsurvey_by_testinvite($testinvite);

			sleep(2); // Explicitly wait some time to make sure results are stored

			$this->load->model('surveyModel');
			$result = $this->surveyModel->get_result_array_by_token($testsurvey->limesurvey_id, $token);
            $date = $this->surveyModel->get_submit_date($testsurvey->limesurvey_id, $token);

			$this->add_scores($testinvite, $result, $date);

			redirect('c/' . $test_code . '/' . $token . '/home');
		}
	}

	/**
	 * Creates an ad-hoc participant/testInvite and proceeds to fill the scores.
	 * @param string $test_code
	 * @param int $response_id
	 */
	public function add_participant($test_code, $response_id)
	{
		if (!SURVEY_DEV_MODE)
		{
			$test = $this->testModel->get_test_by_code($test_code);
			$testsurveys = $this->testSurveyModel->get_testsurveys_by_test($test->id);

			// Set $testsurvey to the first item, unless we found none or multiple
			if (!$testsurveys)
			{
				echo 'We could not create an ad-hoc participant: no questionnaire link found.';
				return;
			}
			else if (count($testsurveys) > 2)
			{
				echo 'We could not create an ad-hoc participant: multiple questionnaire links found, so not clear which to use.';
				return;
			}
			$testsurvey = $testsurveys[0];

			sleep(2); // Explicitly wait some time to make sure results are stored

			$this->load->model('surveyModel');
			$result = $this->surveyModel->get_result_array_by_id($testsurvey->limesurvey_id, $response_id, FALSE);
			$mapping = $this->testSurveyMappingModel->get_mapping_by_testsurvey($testsurvey->id, 'participant');

			// Check if participant exists
			$firstname = $result[$mapping->firstname];
			$lastname = $result[$mapping->lastname];
			$gender = strtolower($result[$mapping->gender]);
			$dob = input_date($result[$mapping->dateofbirth]);
			$participants = $this->participantModel->find_participants_by_name_gender_birth($firstname, $lastname, $gender, $dob);

			// If we find only one, set the $participant_id to the one found
			if (count($participants) === 1)
			{
				$participant_id = $participants[0]->id;
			}
			// Otherwise, we can't be sure, create a new partipant, set as deactivated
			else
			{
				$m = $result[$mapping->multilingual] === 'Y';
				$d = !$result[$mapping->dyslexicparent] ? NULL : $result[$mapping->dyslexicparent];
				$p = !$result[$mapping->problemsparent] ? NULL : $result[$mapping->problemsparent];

				$participant = array(
					'firstname' 			=> $firstname,
					'lastname' 				=> $lastname,
					'gender' 				=> $gender,
					'dateofbirth'			=> $dob,
					'birthweight' 			=> $result[$mapping->birthweight],
					'pregnancyweeks' 		=> $result[$mapping->pregnancyweeks],
					'pregnancydays' 		=> $result[$mapping->pregnancydays],
					'phone' 				=> '',
					'email'					=> $result[$mapping->email],
					'multilingual' 			=> $m,
					'dyslexicparent' 		=> $d,
					'problemsparent' 		=> $p,
					'deactivated'			=> input_datetime(),
					'deactivated_reason'	=> DeactivateReason::FromSurvey,
				);
				$participant_id = $this->participantModel->add_participant($participant);
			}

			// Create an ad-hoc testInvite and fill the scores
			$testinvite = $this->testInviteModel->create_testinvite($testsurvey->id, $participant_id);
			$this->add_scores($testinvite, $result, input_date());

			// Send an e-mail with the URL to the results page
			$this->send_completion_email($testinvite);

			redirect('c/' . $test_code . '/' . $testinvite->token . '/home');
		}
	}

	private function send_completion_email($testinvite)
	{
		$participant = $this->testInviteModel->get_participant_by_testinvite($testinvite);
		$test = $this->testInviteModel->get_test_by_testinvite($testinvite);
		$template = $this->testTemplateModel->get_testtemplate_by_test($test->id, L::Dutch);

		// Email to participant
		$message = email_replace($template->template . '_completion', $participant, NULL, NULL, $testinvite);

		$this->email->clear();
		$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
		$this->email->to(in_development() ? TO_EMAIL_OVERRIDE : $participant->email);
		$this->email->subject('Babylab Utrecht: Bedankt voor het invullen van de vragenlijst');
		$this->email->message($message);
		$this->email->send();
	}

	/**
	 * Adds scores to a testInvite and sets it as completed.
	 * @param testInvite $testinvite
	 * @param array $result
	 * @param date $date_completed
	 */
	private function add_scores($testinvite, $result, $date_completed)
	{
		$test = $this->testInviteModel->get_test_by_testinvite($testinvite);

		foreach ($result as $question => $answer)
		{
			$testcat = $this->testCatModel->get_testcat_by_question_id($test, $question);

			if ($testcat)
			{
				$score_type = $testcat->score_type;

				switch ($score_type)
				{
					case 'bool':
						switch ($answer)
						{
							case 'Y'	: $answer = true; break;
							case 'N'	: $answer = false; break;
						}
					case 'int': 	$answer = (int) $answer; break;
					case 'date': 	break;
					case 'string': 	break;
				}

				$score = array(
					'testcat_id'			=> $testcat->id,
					'testinvite_id' 		=> $testinvite->id,
					'score'					=> $answer,
					'date'					=> $date_completed,
				);

				$this->scoreModel->add_score($score);
			}
		}

		$this->testInviteModel->set_completed($testinvite->id, $date_completed);
	}

	/////////////////////////
	// Helpers
	/////////////////////////

	private function view_code($test, $page)
	{
		return substr($test->code, 0, 4) . '/' . $page;
	}

	private function add_comments_to_score($scores, $participant)
	{
		$comments = array();

		// Check for the minimum percentile and language age TODO: maybe add this as a column on test
		$percentile_check = $this->check_minimum_percentile($scores);
		$language_age_check = $this->check_language_age($scores);

		// Situation C: trouble in all sections.
		if ($percentile_check == 2 && $language_age_check == 2)
		{
			array_push($comments, sprintf(lang('ncdi_A'), gender_child($participant->gender), gender_sex($participant->gender)));
		}
		// Situation B: trouble in some sections.
		else if ($percentile_check == 1 || $language_age_check == 1)
		{
			array_push($comments, lang('ncdi_B'));
			// TODO: in this case we should create a new testinvite
		}
		// Situation A: all is fine.
		else
		{
			array_push($comments, lang('ncdi_C'));
		}

		// Check the competence score against the production score
		if ($this->check_comp_vs_prod($scores))
		{
			// Add comment if not OK.
			array_push($comments, lang('ncdi_comp_vs_prod'));
		}

		return $comments;
	}

	/**
	 *
	 * Checks for a minimum score.
	 * Under normal circumstances, no score should be below the minimum score.
	 * @param $scores
	 * @return integer Returns 2 if all scores below minimum, 1 for at least one, and 0 for no scores below minimum.
	 */
	private function check_minimum_percentile($scores)
	{
		$one = FALSE;
		$all = TRUE;

		foreach ($scores as $score)
		{
			if (in_array($score['code'], array('b', 'p')))
			{
				if ($score['percentile'] < NCDI_MINIMUM_PERCENTILE)
				{
					$one = TRUE;
				}
				else
				{
					$all = FALSE;
				}
			}
		}

		return $all ? 2 : ($one ? 1 : 0);
	}

	/**
	 *
	 * Checks for language age.
	 * Under normal circumstances, no score should more than 4 months behind.
	 * @param $scores
	 * @return integer Returns 2 if all scores more than 4 months behind, 1 for at least one, and 0 for no scores behind.
	 */
	private function check_language_age($scores)
	{
		$one = FALSE;
		$all = TRUE;

		foreach ($scores as $score)
		{
			if (in_array($score['code'], array('b', 'p')))
			{
				if ($score['score_age'] - $score['age'] >= NCDI_LANGUAGE_AGE_DIFF)
				{
					$one = TRUE;
				}
				else
				{
					$all = FALSE;
				}
			}
		}

		return $all ? 2 : ($one ? 1 : 0);
	}

	/**
	 *
	 * Checks competence vs. production.
	 * Under normal circumstances, competence should be at least as high as production.
	 * @param array $scores
	 */
	private function check_comp_vs_prod($scores)
	{
		foreach ($scores as $score)
		{
			if ($score['code'] === 'b') $comp = $score['percentile'];
			if ($score['code'] === 'p') $prod = $score['percentile'];
		}
		return $comp < $prod;
	}

	private function get_test_or_die($test_code)
	{
		$test = $this->testModel->get_test_by_code($test_code);
		if (empty($test)) show_404();
		return $test;
	}

	private function set_test_data($data, $test)
	{
		$data['test'] 		= $test;
		$data['test_id'] 	= $test->id;
		$data['test_code']	= $test->code;
		$data['test_name']	= $test->name;
		return $data;
	}

	private function set_token_data($data, $token)
	{
		// Check whether we received a token
		if ($token)
		{
			// Check whether this is an existing token
			$testinvite = $this->testInviteModel->get_testinvite_by_token($token);
			if ($testinvite)
			{
				// Check whether this token is for the correct test
				$testsurvey = $this->testInviteModel->get_testsurvey_by_testinvite($testinvite);
				if ($testsurvey->test_id === $data['test_id'])
				{
					// OK, fill data!
					$data['token'] = $token;
					$data['valid_token'] = TRUE;
					$data['testinvite'] = $testinvite;
					$data['testinvite_id'] = $testinvite->id;
					$data['test_date'] = output_date($testinvite->datecompleted);

					$participant = $this->testInviteModel->get_participant_by_testinvite($testinvite);
					$data['participant_id'] = $participant->id;
					$data['gender'] = gender_sex($participant->gender);
					$data['gender_child'] = gender_child($participant->gender);
					$data['age_in_months'] = age_in_months($participant, $testinvite->datecompleted);

					return $data;
				}
			}
		}

		// Default values
		$data['valid_token'] = FALSE;
		$data['participant_id'] = 0;
		$data['gender'] = gender_sex(Gender::Male);

		return $data;
	}

	/////////////////////////
	// JSON
	/////////////////////////

	public function percentiles($test_code, $testinvite_id = NULL)
	{
		$table = array();
		$table['cols'] = array(
		array('label' => lang('testcat'), 'type' => 'string'),
		array('label' => lang('gender'), 'type' => 'string'),
		array('label' => lang('age'), 'type' => 'number'),
		array('label' => '50ste percentiel', 'type' => 'number'),
		array('type' => 'string', 'role' => 'tooltip'),
		array('label' => '99e percentiel', 'type' => 'number', 'role' => 'interval'),
		array('label' => '85e percentiel', 'type' => 'number', 'role' => 'interval'),
		array('label' => '15e percentiel', 'type' => 'number', 'role' => 'interval'),
		array('label' => '1e percentiel', 'type' => 'number', 'role' => 'interval')
		);

		if ($testinvite_id)
		{
			$testinvite = $this->testInviteModel->get_testinvite_by_id($testinvite_id);
			$participant = $this->testInviteModel->get_participant_by_testinvite($testinvite);
			$p_gender = $participant->gender;
			$p_age = age_in_months($participant, $testinvite->datecompleted);
			array_push($table['cols'], array('label' => 'Score kind', 'type' => 'number'), array('type' => 'string', 'role' => 'tooltip'));
		}

		$testcat_ids = $this->get_testcat_ids($test_code);
		$percentiles1 = $this->percentileModel->get_percentiles_by_testcats($testcat_ids, array(50));
		$percentiles2 = $this->percentileModel->get_percentiles_by_testcats($testcat_ids, array(1, 15, 50, 85, 99));
		$percentiles = array_merge($percentiles1, $percentiles2);

		$rows = array();
		foreach ($percentiles as $percentile)
		{
			$unique = implode('_', array($percentile->testcat_id, $percentile->gender, $percentile->age));
			$testcat = $this->testCatModel->get_testcat_by_id($percentile->testcat_id)->name;
			$gender = !empty($percentile->gender) ? gender_sex($percentile->gender) : NULL;

			$rows[$unique]['t'] = array('v' => $testcat);
			$rows[$unique]['g'] = array('v' => $gender);
			$rows[$unique]['a'] = array('v' => intval($percentile->age));
			$rows[$unique][$percentile->percentile] = array('v' => $percentile->score);
			if ($percentile->percentile == 50) 
			{
				$tooltip = 'Score 50ste percentiel na ' . $percentile->age . ' maanden: ' . $percentile->score;
				$rows[$unique]['tt'] = array('v' => $tooltip);
			}

			if (isset($participant) && $percentile->percentile == 1) // only do this at the last run... TODO: kinda dirty
			{
				if ((empty($percentile->gender) || $percentile->gender === $p_gender) && $percentile->age == $p_age)
				{
					$score = $this->testCatModel->total_score($percentile->testcat_id, $testinvite_id);
					$rows[$unique][100] = array('v' => intval($score->score));
					$tooltip = 'Score kind na ' . $percentile->age . ' maanden: ' . $score->score;
					$rows[$unique][101] = array('v' => $tooltip);
				}
				else
				{
					$rows[$unique][100] = array('v' => NULL);
					$rows[$unique][101] = array('v' => NULL);
				}
			}
		}

		$table['rows'] = $this->flatten($rows);
		echo json_encode($table);
	}

	public function vs_scores($test_code)
	{
		$table = array();
		$table['cols'] = array(
		array('label' => lang('testcat'), 'type' => 'string'),
		array('label' => lang('gender'), 'type' => 'string'),
		array('label' => lang('age'), 'type' => 'number'),
		array('label' => lang('score'), 'type' => 'number'),
		array('label' => '50e percentiel', 'type' => 'number')
		);

		$rows = array();

		$testcat_ids = $this->get_testcat_ids($test_code);

		$nr = 0;
		foreach ($testcat_ids as $testcat_id)
		{
			$scores = $this->testCatModel->total_score_per_testinvite($testcat_id);
			foreach ($scores as $score)
			{
				if ($score->score > 0)
				{
					$testinvite = $this->testInviteModel->get_testinvite_by_id($score->testinvite_id);
					$participant = $this->testInviteModel->get_participant_by_testinvite($testinvite);
					$age = explode(';', age_in_months_and_days($participant->dateofbirth, $testinvite->datecompleted));
					$age = $age[0] + $age[1] / 31; // to evenly divide over months.

					$testcat = $this->testCatModel->get_testcat_by_id($testcat_id);
					// FIXME: quick and dirty fix for percentiles without gender...
					$gender = in_array($testcat->code, array('w', 'z')) ? NULL : gender_sex($participant->gender);

					$rows[$nr][0] = array('v' => $testcat->name);
					$rows[$nr][1] = array('v' => $gender);
					$rows[$nr][2] = array('v' => $age);
					$rows[$nr][3] = array('v' => intval($score->score));
					$rows[$nr][4] = array('v' => NULL);

					$nr++;
				}
			}
		}

		$percentiles = $this->percentileModel->get_percentiles_by_testcats($testcat_ids, array(50));
		foreach ($percentiles as $percentile)
		{
			$testcat = $this->testCatModel->get_testcat_by_id($percentile->testcat_id)->name;
			$gender = !empty($percentile->gender) ? gender_sex($percentile->gender) : NULL;

			$rows[$nr][0] = array('v' => $testcat);
			$rows[$nr][1] = array('v' => $gender);
			$rows[$nr][2] = array('v' => $percentile->age);
			$rows[$nr][3] = array('v' => NULL);
			$rows[$nr][4] = array('v' => $percentile->score);

			$nr++;
		}

		//echo '<pre>' . var_dump($rows) . '</pre>';die;

		$table['rows'] = $this->flatten($rows);
		echo json_encode($table);
	}

	/////////////////////////
	// Helpers
	/////////////////////////

	private function get_testcat_ids($test_code)
	{
		$test = $this->testModel->get_test_by_code($test_code);
		$testcats = $this->testCatModel->get_testcats_by_test($test->id, TRUE);
		return get_object_ids($testcats);
	}

	private function flatten($rows)
	{
		$result = array();
		foreach ($rows as $row) array_push($result, array('c' => array_values($row)));
		return $result;
	}

	/////////////////////////
	// Unused
	/////////////////////////

	public function by_gender()
	{
		$data['page_title'] = lang('percentiles');

		$this->load->view('charts/charts_header', $data);
		$this->load->view('charts/percentiles_by_gender', $data);
		$this->load->view('templates/footer');
	}

	public function percentiles_by_gender()
	{
		$table = array();
		$table['cols'] = array(
		array('label' => 'Age', 'type' => 'number'),
		array('label' => 'Male 50th percentile', 'type' => 'number'),
		array('label' => 'Female 50th percentile', 'type' => 'number')
		);

		$percentiles = $this->percentileModel->get_percentiles_by_testcat(1);

		$rows = array();
		foreach ($percentiles as $percentile)
		{
			if ($percentile->percentile == 50)
			{
				$rows[$percentile->age][0] = array('v' => $percentile->age);
				$rows[$percentile->age][$percentile->gender] = array('v' => $percentile->score);
			}
		}

		$table['rows'] = $this->flatten($rows);
		echo json_encode($table);
	}
}
