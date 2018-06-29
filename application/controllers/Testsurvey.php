<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Testsurvey extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());

		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index()
	{
		$add_url = array('url' => 'testsurvey/add', 'title' => lang('add_testsurvey'));

		create_testsurvey_table();
		$data['ajax_source'] = 'testsurvey/table/';
		$data['page_title'] = lang('testsurveys');
		$data['action_urls'] = array($add_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::ADMIN);
		$this->load->view('templates/footer');
	}

	/** Shows the page for a single testsurvey */
	public function get($testsurvey_id)
	{
		$testsurvey = $this->testSurveyModel->get_testsurvey_by_id($testsurvey_id);
		$test = $this->testSurveyModel->get_test_by_testsurvey($testsurvey);

		$data['testsurvey'] = $testsurvey;
		$data['test'] = $test;
		$data['page_title'] = sprintf(lang('data_for_testsurvey'), testsurvey_name($testsurvey));

		$this->load->view('templates/header', $data);
		$this->load->view('testsurvey_view', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the add testsurvey page */
	public function add($test_id = 0)
	{
		$data['tests'] = $this->testModel->get_all_tests();

		$data['page_title'] = lang('add_testsurvey');
		$data['new_testsurvey'] = TRUE;
		$data['action'] = 'testsurvey/add_submit/';
		$data = add_fields($data, 'testsurvey');

		$data['test_id'] = $test_id;

		$this->load->view('templates/header', $data);
		$this->load->view('testsurvey_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the addition of a testsurvey */
	public function add_submit()
	{
		// Run validation
		if (!$this->validate_testsurvey(TRUE))
		{
			// If not succeeded, show form again with error messages
			$this->add($this->input->post('test'));
		}
		else
		{
			// If succeeded, insert data into database
			$testsurvey = $this->post_testsurvey(TRUE);
			$testsurvey_id = $this->testSurveyModel->add_testsurvey($testsurvey);

			$t = $this->testSurveyModel->get_testsurvey_by_id($testsurvey_id);
			$test = $this->testSurveyModel->get_test_by_testsurvey($t);
			flashdata(sprintf(lang('testsurvey_added'), $test->name));
			redirect('/testsurvey/', 'refresh');
		}
	}

	/** Specifies the contents of the edit testsurvey page */
	public function edit($testsurvey_id)
	{
		$data['tests'] = $this->testModel->get_all_tests();

		$testsurvey = $this->testSurveyModel->get_testsurvey_by_id($testsurvey_id);
		$test = $this->testSurveyModel->get_test_by_testsurvey($testsurvey);
		$data['test'] = $test;

		$data['page_title'] = sprintf(lang('edit_testsurvey'), $test->name);
		$data['new_testsurvey'] = FALSE;
		$data['action'] = 'testsurvey/edit_submit/' . $testsurvey_id;
		$data = add_fields($data, 'testsurvey', $testsurvey);

		$this->load->view('templates/header', $data);
		$this->load->view('testsurvey_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the edit of a testsurvey */
	public function edit_submit($testsurvey_id)
	{
		// Run validation
		if (!$this->validate_testsurvey(FALSE))
		{
			// If not succeeded, show form again with error messages
			$this->edit($testsurvey_id);
		}
		else
		{
			// If succeeded, update data into database
			$testsurvey = $this->post_testsurvey(FALSE);
			$this->testSurveyModel->update_testsurvey($testsurvey_id, $testsurvey);

			$t = $this->testSurveyModel->get_testsurvey_by_id($testsurvey_id);
			$test = $this->testSurveyModel->get_test_by_testsurvey($t);
			flashdata(sprintf(lang('testsurvey_edited'), $test->name));
			redirect('/testsurvey/', 'refresh');
		}
	}

	/** Deletes the specified testsurvey, and returns to previous page */
	public function delete($testsurvey_id)
	{
		$this->testSurveyModel->delete_testsurvey($testsurvey_id);
		flashdata(lang('testsurvey_deleted'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Other views
	/////////////////////////

	/** Finds possible participants for a testsurvey; excludes participants for which an invite has been made */
	public function find($testsurvey_id)
	{
		create_participant_table();
		$data['ajax_source'] = 'participant/table_by_testsurvey/' . $testsurvey_id;
		$data['page_title'] = lang('participants');

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::ADMIN);
		$this->load->view('templates/footer');
	}

	/**
	 * Downloads all scores of participants of a testsurvey as a .csv-file.
	 * @param integer $testsurvey_id
	 */
	public function download_scores($testsurvey_id)
	{
		$testsurvey = $this->testSurveyModel->get_testsurvey_by_id($testsurvey_id);
		$test = $this->testSurveyModel->get_test_by_testsurvey($testsurvey);

		// Retrieve the scores and convert to .csv
		$table = $this->get_results_table($testsurvey_id);
		$csv = scores_to_csv($test->code, $table);
		
		// Generate filename
		$escaped = preg_replace('/[^A-Za-z0-9_\-]/', '_', testsurvey_name($testsurvey));
		$filename = $escaped . '_' . mdate("%Y%m%d_%H%i", time()) . '.csv';
		
		// Download the file
		force_download($filename, $csv); 		
	}

	/**
	 * Returns all scores of a testsurvey as an array.
	 * @param integer $testsurvey_id
	 */
	private function get_results_table($testsurvey_id) 
	{
		$scores = $this->scoreModel->get_scores_by_testsurvey($testsurvey_id);
		
		$result = array();
		foreach ($scores as $score)
		{
			$result[$score->testinvite_id][$score->testcat_id] = $score->score;
		}
		
		return $result;
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates a testsurvey */
	private function validate_testsurvey($new_testsurvey)
	{
		if ($new_testsurvey)
		{
			$this->form_validation->set_rules('test', lang('test'), 'callback_not_zero');
		}
		$whennr_validation = $this->input->post('whensent') === TestWhenSent::MANUAL ? 'trim' : 'trim|required|is_natural_no_zero';

		$this->form_validation->set_rules('limesurvey_id', lang('limesurvey_id'), 'trim|required|is_natural_no_zero|callback_survey_exists');
		$this->form_validation->set_rules('whensent', lang('whensent'), 'trim|required');
		$this->form_validation->set_rules('whennr', lang('whennr'), $whennr_validation);
		$this->form_validation->set_rules('description', lang('survey_description'), 'trim|max_length[200]');

		return $this->form_validation->run();
	}

	/** Posts the data for a testsurvey */
	private function post_testsurvey($new_testsurvey)
	{
		$surveyid = $this->input->post('limesurvey_id');
		$surveyid = empty($surveyid) ? NULL : $surveyid;
		$whennr = $this->input->post('whensent') === TestWhenSent::MANUAL ? NULL : $this->input->post('whennr');

		$testsurvey = array(
				'test_id'		=> $this->input->post('test'),
				'limesurvey_id' => $surveyid,
				'whensent' 		=> $this->input->post('whensent'),
				'whennr' 		=> $whennr,
				'description' 	=> $this->input->post('description'),
		);

		if (!$new_testsurvey) unset($testsurvey['test_id']);

		return $testsurvey;
	}

	/////////////////////////
	// Callbacks
	/////////////////////////

	/** Checks whether the given parameter is higher than 0 */
	public function not_zero($value)
	{
		if (intval($value) <= 0)
		{
			$this->form_validation->set_message('not_zero', lang('form_validation_isset'));
			return FALSE;
		}
		return TRUE;
	}

	/** Checks whether the survey exists */
	public function survey_exists($survey_id)
	{
		if (!SURVEY_DEV_MODE)
		{
			$this->load->model('surveyModel');
			$survey = $this->surveyModel->get_survey_by_id($survey_id);

			if (!$survey)
			{
				$this->form_validation->set_message('survey_exists', lang('survey_does_not_exist'));
				return FALSE;
			}
			return TRUE;
		}
		else
		{
			return TRUE;
		}
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('test.name AS t, limesurvey_id, whensent, whennr,
			testsurvey.id AS name, testsurvey.id AS id, test_id', FALSE);
		$this->datatables->from('testsurvey');
		$this->datatables->join('test', 'test.id = testsurvey.test_id');

		$this->datatables->edit_column('t', '$1', 'test_get_link_by_id(test_id)');
		$this->datatables->edit_column('limesurvey_id', '$1', 'survey_by_id(limesurvey_id)');
		$this->datatables->edit_column('whensent', '$1', 'testsurvey_when(whensent, whennr)');
		$this->datatables->edit_column('name', '$1', 'testsurvey_name_by_id(id)');
		$this->datatables->edit_column('id', '$1', 'testsurvey_actions(id)');

		$this->datatables->unset_column('test_id');
		$this->datatables->unset_column('whennr');

		echo $this->datatables->generate();
	}

	public function table_by_test($test_id)
	{
		$this->datatables->where('test.id', $test_id);
		$this->datatables->unset_column('t');
		$this->table();
	}
}
