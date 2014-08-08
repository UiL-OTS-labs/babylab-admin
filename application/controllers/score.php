<?php
class Score extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		reset_language(current_language());

		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index()
	{
		$edit_all_url = array('url' => 'score/edit_all', 'title' => lang('edit_all_scores'));
		$add_url = array('url' => 'score/add', 'title' => lang('add_score'));

		create_score_table();
		$data['ajax_source'] = 'score/table/';
		$data['page_title'] = lang('scores');
		$data['action_urls'] = array($edit_all_url, $add_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the add (single) score page */
	public function add($test_id = 0, $testcat_id = 0, $testsurvey_id = 0, $participant_id = 0)
	{
		$data['tests'] = $this->testModel->get_all_tests();
		$data['testcats'] = $this->testCatModel->get_all_testcats();
		$data['testsurveys'] = $this->testSurveyModel->get_all_testsurveys();
		$data['participants'] = $this->participantModel->get_all_participants(TRUE);

		$data['page_title'] = lang('add_score');
		$data['new_score'] = TRUE;
		$data['action'] = 'score/add_submit';
		$data = add_fields($data, 'score');

		$data['test_id'] = $test_id;
		$data['testcat_id'] = $testcat_id;
		$data['testsurvey_id'] = $testsurvey_id;
		$data['participant_id'] = $participant_id;
		if (!empty($participant_id))
		{
			$data['participant'] = name($this->participantModel->get_participant_by_id($participant_id));
		}

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('score_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the addition of a (single) score */
	public function add_submit()
	{
		// Run validation
		if (!$this->validate_score(TRUE))
		{
			// If not succeeded, show form again with error messages
			$this->add($this->input->post('test'), $this->input->post('testcat'),
			$this->input->post('testsurvey'), $this->input->post('participant_id'));
		}
		else
		{
			// If succeeded, insert data into database
			$score = $this->post_score(TRUE);
			$score_id = $this->scoreModel->add_score($score);

			$s = $this->scoreModel->get_score_by_id($score_id);
			flashdata(lang('score_added'));
			redirect('/score/', 'refresh');
		}
	}

	/** Specifies the contents of the edit score page */
	public function edit($score_id)
	{
		$score = $this->scoreModel->get_score_by_id($score_id);
		$data['test'] = $this->scoreModel->get_test_by_score($score);
		$data['testcat'] = $this->scoreModel->get_testcat_by_score($score);
		$data['testsurvey'] = $this->scoreModel->get_testsurvey_by_score($score);
		$data['participant'] = $this->scoreModel->get_participant_by_score($score);

		$data['page_title'] = lang('edit_score');
		$data['new_score'] = FALSE;
		$data['action'] = 'score/edit_submit/' . $score_id;
			
		$data = add_fields($data, 'score', $score);
		$data['date'] = output_date($score->date, TRUE);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('score_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the edit of a score */
	public function edit_submit($score_id)
	{
		// Run validation
		if (!$this->validate_score(FALSE))
		{
			// If not succeeded, show form again with error messages
			$this->edit($score_id);
		}
		else
		{
			// If succeeded, update data into database
			$score = $this->post_score(FALSE);
			$this->scoreModel->update_score($score_id, $score);

			$s = $this->scoreModel->get_score_by_id($score_id);
			flashdata(lang('score_edited'));
			redirect('/score/', 'refresh');
		}
	}

	/** Specifies the contents of the edit all scores for a test page TODO: does not work now */
	public function edit_all($test_id = 0, $participant_id = 0)
	{
		$data['tests'] = $this->testModel->get_all_tests();
		$data['participants'] = $this->participantModel->get_all_participants(TRUE);

		$data['page_title'] = lang('edit_all_scores');
		$data['new_score'] = TRUE;
		$data['action'] = 'score/edit_all_submit';
		$data = add_fields($data, 'score');

		$data['test_id'] = $test_id;
		$data['participant_id'] = $participant_id;
		// $data['date'] = output_date(); TODO: find min date for possible scores

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('score_edit_all_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the edit of all scores for a test TODO: does not work now */
	public function edit_all_submit()
	{
		$test_id = $this->input->post('test');
		$participant_id = $this->input->post('participant');
		$testcats = $this->testCatModel->get_testcats_by_test($test_id, FALSE, TRUE);

		$input_scores = array();
		foreach ($testcats as $tc)
		{
			$score = array('testcat' => $tc->id, 'score' => $this->input->post($tc->id));
			array_push($input_scores, $score);
		}

		// Run validation
		if (!$this->validate_all_scores($input_scores))
		{
			// If not succeeded, show form again with error messages
			$this->edit_all($test_id, $participant_id);
		}
		else
		{
			// If succeeded, insert data into database
			$scores = $this->post_all_scores($input_scores);
			foreach ($scores as $s)
			{
				// TODO: replace
				$this->scoreModel->add_or_update_score($s);
			}

			$participant = $this->participantModel->get_participant_by_id($participant_id);
			$test = $this->testModel->get_test_by_id($test_id);
			flashdata(sprintf(lang('all_scores_edited'), name($participant), $test->name));
			redirect('/score/', 'refresh');
		}
	}

	/** Deletes the specified score, and returns to previous page */
	public function delete($score_id)
	{
		$this->scoreModel->delete_score($score_id);
		flashdata(lang('score_deleted'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Other views
	/////////////////////////

	/** Specifies the content of a page with the scores for a specific participant */
	public function participant($participant_id)
	{
		$participant = $this->participantModel->get_participant_by_id($participant_id);

		create_score_table(NULL, 'participant');
		$data['ajax_source'] = 'score/table_by_participant/' . $participant_id;
		$data['page_title'] = sprintf(lang('scores_for'), name($participant));

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the content of a page with the scores for a specific test category */
	public function testcat($testcat_id)
	{
		$testcat = $this->testCatModel->get_testcat_by_id($testcat_id);

		create_score_table(NULL, 'testcat');
		$data['ajax_source'] = 'score/table_by_testcat/' . $testcat_id;
		$data['page_title'] = sprintf(lang('scores_for'), $testcat->name);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the content of a page with the scores for a specific testsurvey */
	public function testsurvey($testsurvey_id)
	{
		$testsurvey = $this->testSurveyModel->get_testsurvey_by_id($testsurvey_id);

		create_score_table(NULL, 'testsurvey');
		$data['ajax_source'] = 'score/table_by_testsurvey/' . $testsurvey_id;
		$data['page_title'] = sprintf(lang('scores_for'), $testsurvey->name);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the content of a page with the scores for a specific testsurvey */
	public function testinvite($testinvite_id)
	{
		$testinvite = $this->testInviteModel->get_testinvite_by_id($testinvite_id);
		$test = $this->testInviteModel->get_test_by_testinvite($testinvite);
		$participant = $this->testInviteModel->get_participant_by_testinvite($testinvite);

		create_score_table(NULL, 'testinvite');
		$data['ajax_source'] = 'score/table_by_testinvite/' . $testinvite_id;
		$data['page_title'] = sprintf(lang('scores_for'), name($participant));
		$data['page_info'] = 'Bekijk het score-overzicht via ' . anchor('c/' . $test->code . '/' . $testinvite->token . '/home', 'deze link') . '.';

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates a score */
	private function validate_score($new_score)
	{
		if ($new_score)
		{
			$this->form_validation->set_rules('testcat', lang('testcat'), 'callback_not_zero');
			$this->form_validation->set_rules('testsurvey', lang('testsurvey'), 'callback_not_zero');
			$this->form_validation->set_rules('participant_id', lang('participant'), 'callback_not_zero|callback_testinvite_exists|callback_unique_score');
		}
		$this->form_validation->set_rules('score', lang('score'), 'trim|required|integer');
		$this->form_validation->set_rules('date', lang('date'), 'trim|required');

		return $this->form_validation->run();
	}

	/** Posts the data for a score */
	private function post_score($new_score)
	{
		if ($new_score)
		{
			$testsurvey_id = $this->input->post('testsurvey');
			$participant_id = $this->input->post('participant_id');
			$testinvite = $this->testInviteModel->get_testinvite_by_testsurvey_participant($testsurvey_id, $participant_id);

			return array(
					'testcat_id'	=> $this->input->post('testcat'),
					'testinvite_id' => $testinvite->id,
					'score' 		=> $this->input->post('score'),
					'date' 			=> input_date($this->input->post('date'))
			);
		}
		else
		{
			return array(
					'score' 		=> $this->input->post('score'),
					'date' 			=> input_date($this->input->post('date'))
			);
		}
	}

	/** Validates a score */
	private function validate_all_scores($scores)
	{
		$this->form_validation->set_rules('test', lang('test'), 'callback_not_zero');
		$this->form_validation->set_rules('participant', lang('participant'), 'callback_not_zero');
		$this->form_validation->set_rules('date', lang('date'), 'trim|required');

		// TODO: validate individual scores?!

		return $this->form_validation->run();
	}

	/** Posts the data for a score */
	private function post_all_scores($scores)
	{
		$result = array();
		foreach ($scores as $s)
		{
			if (!empty($s['score']))
			{
				$score = array(
					'participant_id'=> $this->input->post('participant'),
					'testcat_id'	=> $s['testcat'],
					'score' 		=> $s['score'],
					'date' 			=> input_date($this->input->post('date')));
				array_push($result, $score);
			}
		}
		return $result;
	}

	/////////////////////////
	// Callbacks
	/////////////////////////

	/** Checks whether the given parameter is higher than 0 */
	public function not_zero($value)
	{
		if (intval($value) <= 0)
		{
			$this->form_validation->set_message('not_zero', lang('isset'));
			return FALSE;
		}
		return TRUE;
	}

	/** Checks whether the current score does not exist for this test category and participant */
	public function unique_score($testsurvey_id)
	{
		$participant_id = $this->input->post('participant_id');
		$testinvite = $this->testInviteModel->get_testinvite_by_testsurvey_participant($testsurvey_id, $participant_id);
		if (empty($testinvite)) return FALSE;

		$testcat_id = $this->input->post('testcat');
		$score = $this->scoreModel->get_score_by_testcat_testinvite($testcat_id, $testinvite->id);
		if (!empty($score))
		{
			$this->form_validation->set_message('unique_score', lang('unique_score'));
			return FALSE;
		}
		return TRUE;
	}

	/** Checks whether the for this participant an invitation has already been created */
	public function testinvite_exists($participant_id)
	{
		$testsurvey_id = $this->input->post('testsurvey');
		$testinvite = $this->testInviteModel->get_testinvite_by_testsurvey_participant($testsurvey_id, $participant_id);
		if (empty($testinvite))
		{
			$participant = $this->participantModel->get_participant_by_id($participant_id);
			$testsurvey = $this->testSurveyModel->get_testsurvey_by_id($testsurvey_id);
			$this->form_validation->set_message('testinvite_exists', sprintf(lang('testinvite_not_exists'), name($participant), testsurvey_name($testsurvey)));
			return FALSE;
		}
		return TRUE;
	}

	/////////////////////////
	// Helpers
	/////////////////////////

	/** Filters the testcats by test on the add (single) page. */
	public function filter_testcats()
	{
		$test_id = $this->input->post('test_id');
		$testcats = $this->testCatModel->get_testcats_by_test($test_id, FALSE, TRUE);
		echo form_dropdown_and_label('testcat', testcat_options($testcats, FALSE));
	}

	/** Filters the testsurveys by test on the add (single) page. */
	public function filter_testsurveys()
	{
		$test_id = $this->input->post('test_id');
		$testsurveys = $this->testSurveyModel->get_testsurveys_by_test($test_id);
		echo form_dropdown_and_label('testsurvey', testsurvey_options($testsurveys));
	}

	/** Shows all testcats and scores by test on the add (all) page. */
	public function show_all_testcats()
	{
		$participant_id = $this->input->post('participant');
		$test_id = $this->input->post('test');

		if (empty($participant_id) || empty($test_id))
		{
			echo lang('select_test_participant');
			return;
		}

		$testcats = $this->testCatModel->get_testcats_by_test($test_id, FALSE, TRUE);

		if (empty($testcats))
		{
			echo lang('no_results_found');
			return;
		}

		echo '<div class="pure-g-r">';

		foreach ($testcats as $tc)
		{
			$score = $this->scoreModel->get_score($tc->id, $participant_id);
			$value = !empty($score) ? $score->score : '';

			echo '<div class="pure-u-1-3">';
			echo form_label(testcat_code_name($tc), $tc->id);
			echo form_input($tc->id, $value, 'class="positive-integer" placeholder =' . lang('score'));
			echo '</div>';
		}

		echo '</div>';
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('test.name AS ts, CONCAT(testcat.code, testcat.name) AS tc, CONCAT(firstname, lastname) AS p,
			score, date, date AS age, score.id AS id, 
			testsurvey_id, testcat_id, participant.id AS participant_id', FALSE);
		$this->datatables->from('score');
		$this->datatables->join('testcat', 'testcat.id = score.testcat_id');
		$this->datatables->join('test', 'test.id = testcat.test_id');
		$this->datatables->join('testinvite', 'testinvite.id = score.testinvite_id');
		$this->datatables->join('participant', 'participant.id = testinvite.participant_id');
		$this->datatables->join('testsurvey', 'testsurvey.id = testinvite.testsurvey_id');

		$this->datatables->edit_column('ts', '$1', 'testsurvey_get_link_by_id(testsurvey_id)');
		$this->datatables->edit_column('tc', '$1', 'testcat_get_link_by_id(testcat_id)');
		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(participant_id)');
		$this->datatables->edit_column('age', '$1', 'age_in_months_by_id(participant_id, date)');
		$this->datatables->edit_column('date', '$1', 'output_date(date)');
		$this->datatables->edit_column('id', '$1', 'score_actions(id)');

		$this->datatables->unset_column('testsurvey_id');
		$this->datatables->unset_column('testcat_id');
		$this->datatables->unset_column('participant_id');

		echo $this->datatables->generate();
	}

	public function table_by_participant($participant_id)
	{
		$this->datatables->where('participant.id', $participant_id);
		$this->datatables->unset_column('p');
		$this->table();
	}

	public function table_by_testcat($testcat_id)
	{
		$this->datatables->where('testcat_id', $testcat_id);
		$this->datatables->unset_column('tc');
		$this->table();
	}

	public function table_by_testsurvey($testsurvey_id)
	{
		$this->datatables->where('testsurvey_id', $testsurvey_id);
		$this->datatables->unset_column('ts');
		$this->table();
	}

	public function table_by_testinvite($testinvite_id)
	{
		$this->datatables->where('testinvite_id', $testinvite_id);
		$this->datatables->unset_column('ts');
		$this->datatables->unset_column('p');
		$this->table();
	}
}
