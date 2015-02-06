<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller
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
		$add_url = array('url' => 'test/add', 'title' => lang('add_test'));

		create_test_table();
		$data['ajax_source'] = 'test/table/';
		$data['page_title'] = lang('tests');
		$data['action_urls'] = array($add_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/** Shows the page for a single test */
	public function get($test_id)
	{
		$test = $this->testModel->get_test_by_id($test_id);

		$data['test'] = $test;
		$data['page_title'] = sprintf(lang('data_for_test'), $test->name);

		$this->load->view('templates/header', $data);
		$this->load->view('test_view', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the add test page */
	public function add()
	{
		$data['page_title'] = lang('add_test');
		$data['action'] = 'test/add_submit/';
		$data = add_fields($data, 'test');

		$this->load->view('templates/header', $data);
		$this->load->view('test_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the addition of a test */
	public function add_submit()
	{
		// Run validation
		if (!$this->validate_test())
		{
			// If not succeeded, show form again with error messages
			$this->add();
		}
		else
		{
			// If succeeded, insert data into database
			$test = $this->post_test();
			$test_id = $this->testModel->add_test($test);

			$t = $this->testModel->get_test_by_id($test_id);
			flashdata(sprintf(lang('test_added'), $t->name));
			redirect('/test/', 'refresh');
		}
	}

	/** Specifies the contents of the edit test page */
	public function edit($test_id)
	{
		$test = $this->testModel->get_test_by_id($test_id);

		$data['page_title'] = sprintf(lang('edit_test'), $test->name);
		$data['action'] = 'test/edit_submit/' . $test_id;
		$data = add_fields($data, 'test', $test);

		$this->load->view('templates/header', $data);
		$this->load->view('test_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the edit of a test */
	public function edit_submit($test_id)
	{
		// Run validation
		if (!$this->validate_test($test_id))
		{
			// If not succeeded, show form again with error messages
			$this->edit($test_id);
		}
		else
		{
			// If succeeded, update data into database
			$test = $this->post_test();
			$this->testModel->update_test($test_id, $test);

			$t = $this->testModel->get_test_by_id($test_id);
			flashdata(sprintf(lang('test_edited'), $t->name));
			redirect('/test/', 'refresh');
		}
	}

	/** Deletes the specified test, and returns to previous page */
	public function delete($test_id)
	{
		$this->testModel->delete_test($test_id);
		flashdata(lang('test_deleted'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Results
	/////////////////////////

	/**
	 *
	 * Specifies the contents of the result page.
	 * @deprecated
	 * @param int $testinvite_id
	 */
	public function results($testinvite_id)
	{
		if (!SURVEY_DEV_MODE)
		{
			$testinvite = $this->testInviteModel->get_testinvite_by_id($testinvite_id);
			$testsurvey = $this->testInviteModel->get_testsurvey_by_testinvite($testinvite);
			$this->load->model('surveyModel');
			$result = $this->surveyModel->get_result_by_token($testsurvey->limesurvey_id, $testinvite->token);
			$result_array = $this->surveyModel->get_result_array($testsurvey->limesurvey_id, $testinvite->token);

			$test = $this->testInviteModel->get_test_by_testinvite($testinvite);
			$participant = $this->testInviteModel->get_participant_by_testinvite($testinvite);

			$data['page_title'] = sprintf(lang('scores_for'), name($participant));
			$data['result'] = $result;
			$data['result_array'] = $result_array;
			$data['test'] = $test;

			$this->load->view('templates/header', $data);
			$this->authenticate->authenticate_redirect('test_results_view', $data, UserRole::Leader);
			$this->load->view('templates/footer');
		}
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates a test */
	private function validate_test($test_id = 0)
	{
		$this->form_validation->set_rules('code', lang('code'), 'trim|required|callback_unique_code[' . $test_id . ']');
		$this->form_validation->set_rules('name', lang('name'), 'trim|required');

		return $this->form_validation->run();
	}

	/** Posts the data for a test */
	private function post_test()
	{
		return array(
				'code'		=> $this->input->post('code'),
				'name' 		=> $this->input->post('name'),
		);
	}

	/////////////////////////
	// Callbacks
	/////////////////////////

	/** Checks whether the current code does yet exist */
	public function unique_code($code, $test_id)
	{
		$test = $this->testModel->get_test_by_code($code);
		if ($test && $test->id != $test_id)
		{
			$this->form_validation->set_message('unique_code', lang('is_unique'));
			return FALSE;
		}
		return TRUE;
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('code, name, id', FALSE);
		$this->datatables->from('test');

		$this->datatables->edit_column('name', '$1', 'test_get_link_by_id(id)');
		$this->datatables->edit_column('id', '$1', 'test_actions(id)');

		$this->datatables->unset_column('whennr');

		echo $this->datatables->generate();
	}
}
