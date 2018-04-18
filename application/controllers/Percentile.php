<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Percentile extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except(array('find', 'find_age'));
		reset_language(current_language());

		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index()
	{
		$add_url = array('url' => 'percentile/add', 'title' => lang('add_percentile'));

		create_percentile_table();
		$data['ajax_source'] = 'percentile/table/';

		$data['page_title'] = lang('percentiles');
		$data['action_urls'] = array($add_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the add percentile page */
	public function add($test_id = 0, $testcat_id = 0)
	{
		$data['tests'] = $this->testModel->get_all_tests();
		$data['testcats'] = $this->testCatModel->get_all_testcats();

		$data['page_title'] = lang('add_percentile');
		$data['new_percentile'] = TRUE;
		$data['action'] = 'percentile/add_submit';
		$data = add_fields($data, 'percentile');

		$data['test_id'] = $test_id;
		$data['testcat_id'] = $testcat_id;

		$this->load->view('templates/header', $data);
		$this->load->view('percentile_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the addition of a percentile */
	public function add_submit()
	{
		// Run validation
		if (!$this->validate_percentile(TRUE)) {
			// If not succeeded, show form again with error messages
			$this->add($this->input->post('test'), $this->input->post('testcat'));
		}
		else {
			// If succeeded, insert data into database
			$percentile = $this->post_percentile(TRUE);
			$percentile_id = $this->percentileModel->add_percentile($percentile);

			$s = $this->percentileModel->get_percentile_by_id($percentile_id);
			flashdata(lang('percentile_added'));
			redirect('/percentile/', 'refresh');
		}
	}

	/** Specifies the contents of the edit percentile page */
	public function edit($percentile_id)
	{
		$percentile = $this->percentileModel->get_percentile_by_id($percentile_id);
		$test = $this->percentileModel->get_test_by_percentile($percentile);
		$testcat = $this->percentileModel->get_testcat_by_percentile($percentile);

		$data['page_title'] = lang('edit_percentile');
		$data['new_percentile'] = FALSE;
		$data['test'] = $test;
		$data['testcat'] = $testcat;
		$data['tests'] = $this->testModel->get_all_tests();
		$data = add_fields($data, 'percentile', $percentile);
		$data['action'] = 'percentile/edit_submit/' . $percentile_id;

		$this->load->view('templates/header', $data);
		$this->load->view('percentile_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the edit of a percentile */
	public function edit_submit($percentile_id)
	{
		// Run validation
		if (!$this->validate_percentile(FALSE)) {
			// If not succeeded, show form again with error messages
			$this->edit($percentile_id);
		}
		else {
			// If succeeded, update data into database
			$percentile = $this->post_percentile(FALSE);
			$this->percentileModel->update_percentile($percentile_id, $percentile);

			$s = $this->percentileModel->get_percentile_by_id($percentile_id);
			flashdata(lang('percentile_edited'));
			redirect('/percentile/', 'refresh');
		}
	}

	/** Deletes the specified percentile, and returns to previous page */
	public function delete($percentile_id)
	{
		$this->percentileModel->delete_percentile($percentile_id);
		flashdata(lang('percentile_deleted'));
		redirect('percentile', 'refresh');
	}

	/////////////////////////
	// Other views
	/////////////////////////

	/** Specifies the content of a page with the percentiles for a specific test */
	public function test($test_id)
	{
		$test = $this->testModel->get_test_by_id($test_id);
		$percentiles = $this->percentileModel->get_percentiles_by_test($test_id);

		$data['table'] = create_percentile_table($percentiles);
		$data['page_title'] = sprintf(lang('percentiles_for'), $test->name);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates a percentile */
	private function validate_percentile($new_percentile)
	{
		if ($new_percentile)
		{
			$this->form_validation->set_rules('testcat', lang('testcat'), 'callback_not_zero');
		}
		$this->form_validation->set_rules('score', lang('score'), 'trim|required|integer');
		$this->form_validation->set_rules('percentile', lang('percentile'), 'trim|required|integer|greater_than[0]|less_than[100]');

		return $this->form_validation->run();
	}

	/** Posts the data for a percentile */
	private function post_percentile($new_percentile)
	{
		if ($new_percentile)
		{
			return array(
					'testcat_id'	=> $this->input->post('testcat'),
					'gender'		=> $this->input->post('gender'),
					'age'			=> $this->input->post('age'),
					'score'			=> $this->input->post('score'),
					'percentile' 	=> $this->input->post('percentile')
			);
		}
		else
		{
			return array(
					'score'			=> $this->input->post('score'),
					'gender'		=> $this->input->post('gender'),
					'age'			=> $this->input->post('age'),
					'percentile' 	=> $this->input->post('percentile')
			);
		}
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

	// TODO: check for min/max

	/////////////////////////
	// Helpers
	/////////////////////////

	/** Filters the testcats by test on the add page. */
	public function filter_testcats()
	{
		$test_id = $this->input->post('test_id');
		$testcats = $this->testCatModel->get_testcats_by_test($test_id, TRUE);
		echo form_dropdown_and_label('testcat', testcat_options($testcats));
	}

	/////////////////////////
	// AJAX calls (from e.g. LimeSurvey)
	/////////////////////////

	/** Finds the correct percentile via AJAX */
	public function find()
	{
		$params = $this->get_params();
		$percentile = $this->percentileModel->find_percentile($params['testcat_id'], $params['gender'], $params['age'], $params['score']);
		echo $this->input->get('callback') . '(' . json_encode(array('percentile' => $percentile)) . ')'; // for JSONP
	}

	/** Finds the language age via AJAX */
	public function find_age()
	{
		$params = $this->get_params();
		$age = $this->percentileModel->find_50percentile_age($params['testcat_id'], $params['gender'], $params['score']);
		echo $this->input->get('callback') . '(' . json_encode(array('age' => $age)) . ')'; // for JSONP
	}

	/** Gets the parameters for the above functions */
	private function get_params()
	{
		$result = array();

		$testcode = $this->input->get('test');
		$testcatcode = $this->input->get('testcat');

		$test = $this->testModel->get_test_by_code($testcode);
		$testcat = $this->testCatModel->get_testcat_by_code($test, $testcatcode);
		$gender = $this->input->get('gender');

		$result['testcat_id'] = $testcat->id;
		$result['gender'] = empty($gender) ? NULL : strtolower($this->input->get('gender'));
		$result['age'] = $this->input->get('age');
		$result['score'] = $this->input->get('score');

		return $result;
	}

	/////////////////////////
	// Table
	/////////////////////////

	/** Creates the table with percentile data */
	public function table()
	{
		$this->datatables->select('testcat.test_id AS test_id, testcat_id, gender, age, score, percentile, percentile.id AS id');
		$this->datatables->from('percentile');
		$this->datatables->join('testcat', 'testcat.id = percentile.testcat_id');

		$this->datatables->edit_column('test_id', '$1', 'test_get_link_by_id(test_id)');
		$this->datatables->edit_column('testcat_id', '$1', 'testcat_get_link_by_id(testcat_id)');
		$this->datatables->edit_column('gender', '$1', 'gender(gender)');
		$this->datatables->edit_column('id', '$1', 'percentile_actions(id)');

		echo $this->datatables->generate();
	}

	public function table_by_testcat($testcat_id)
	{
		$this->datatables->where('testcat_id', $testcat_id);
		$this->datatables->unset_column('test_id');
		$this->datatables->unset_column('testcat_id');
		$this->table();
	}
}
