<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TestCat extends CI_Controller
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
		$add_url = array('url' => 'testcat/add', 'title' => lang('add_testcat'));

		create_testcat_table();
		$data['ajax_source'] = 'testcat/table_roots/';
		$data['page_title'] = lang('testcats');
		$data['action_urls'] = array($add_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/** Shows the page for a single testcat */
	public function get($testcat_id)
	{
		$testcat = $this->testCatModel->get_testcat_by_id($testcat_id);

		$data['testcat'] = $testcat;
		$data['test'] = $this->testCatModel->get_test_by_testcat($testcat);
		$data['page_title'] = sprintf(lang('data_for_testcat'), $testcat->name);

		if ($this->testCatModel->has_children($testcat_id) || $testcat->parent_id == NULL)
		{
			$data['is_parent'] = TRUE;
			$data['testinvites'] = $this->testInviteModel->get_testinvites_by_test($testcat->test_id);
		}
		else
		{
			$data['is_parent'] = FALSE;
			$data['parent_testcat'] = $this->testCatModel->get_parent($testcat);
		}

		$this->load->view('templates/header', $data);
		$this->load->view('testcat_view', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the add testcat page */
	public function add($test_id = 0, $testcat_id = 0)
	{
		$data['tests'] = $this->testModel->get_all_tests();
		$data['testcats'] = $this->testCatModel->get_all_testcats();

		$data['page_title'] = lang('add_testcat');
		$data['new_testcat'] = TRUE;
		$data['action'] = 'testcat/add_submit';
		$data = add_fields($data, 'testcat');

		$data['test_id'] = $test_id;
		$data['testcat_id'] = $testcat_id;

		$this->load->view('templates/header', $data);
		$this->load->view('testcat_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the addition of a testcat */
	public function add_submit()
	{
		// Run validation
		if (!$this->validate_testcat(TRUE))
		{
			// If not succeeded, show form again with error messages
			$this->add($this->input->post('test'), $this->input->post('parent_testcat'));
		}
		else
		{
			// If succeeded, insert data into database
			$testcat = $this->post_testcat(TRUE);
			$testcat_id = $this->testCatModel->add_testcat($testcat);

			$t = $this->testCatModel->get_testcat_by_id($testcat_id);
			flashdata(sprintf(lang('testcat_added'), $t->name));
			redirect('/testcat/', 'refresh');
		}
	}

	/** Specifies the contents of the edit testcat page */
	public function edit($testcat_id)
	{
		$data['tests'] = $this->testModel->get_all_tests();

		$testcat = $this->testCatModel->get_testcat_by_id($testcat_id);
		$data['parent_testcat'] = $this->testCatModel->get_parent($testcat);
		$data['test'] = $this->testCatModel->get_test_by_testcat($testcat);

		$data['page_title'] = sprintf(lang('edit_testcat'), $testcat->name);
		$data['new_testcat'] = FALSE;
		$data['action'] = 'testcat/edit_submit/' . $testcat_id;
		$data = add_fields($data, 'testcat', $testcat);

		$this->load->view('templates/header', $data);
		$this->load->view('testcat_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the edit of a testcat */
	public function edit_submit($testcat_id)
	{
		// Run validation
		if (!$this->validate_testcat(FALSE))
		{
			// If not succeeded, show form again with error messages
			$this->edit($testcat_id);
		}
		else
		{
			// If succeeded, update data into database
			$testcat = $this->post_testcat(FALSE);
			$this->testCatModel->update_testcat($testcat_id, $testcat);

			$t = $this->testCatModel->get_testcat_by_id($testcat_id);
			flashdata(sprintf(lang('testcat_edited'), $t->name));
			redirect('/testcat/', 'refresh');
		}
	}

	/** Deletes the specified testcat, and returns to previous page */
	public function delete($testcat_id)
	{
		$this->testCatModel->delete_testcat($testcat_id);
		flashdata(lang('testcat_deleted'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates a testcat */
	private function validate_testcat($new_testcat)
	{
		if ($new_testcat)
		{
			$this->form_validation->set_rules('test', lang('test'), 'callback_not_zero');
			$this->form_validation->set_rules('parent_testcat', lang('parent_testcat'), 'callback_not_zero');
		}
		$this->form_validation->set_rules('name', lang('name'), 'trim|required');
		$this->form_validation->set_rules('code', lang('code'), 'trim|required');

		return $this->form_validation->run();
	}

	/** Posts the data for a testcat */
	private function post_testcat($new_testcat)
	{
		if ($new_testcat)
		{
			$parent = $this->input->post('parent_testcat');
				
			return array(
				'test_id'	=> $this->input->post('test'),
				'parent_id'	=> $parent == '0' ? NULL : $parent,
				'name'		=> $this->input->post('name'),
				'code' 		=> $this->input->post('code')
			);
		}
		else
		{
			return array(
				'name'		=> $this->input->post('name'),
				'code' 		=> $this->input->post('code')
			);
		}
	}

	/////////////////////////
	// Callbacks
	/////////////////////////

	/** Checks whether the given parameter is higher or equal to 0 */
	public function not_zero($value)
	{
		if (intval($value) < 0)
		{
			$this->form_validation->set_message('not_zero', lang('isset'));
			return FALSE;
		}
		return TRUE;
	}

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
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('test.name AS t, CONCAT(testcat.code, testcat.name) AS name, testcat.id AS id, test_id', FALSE);
		$this->datatables->from('testcat');
		$this->datatables->join('test', 'test.id = testcat.test_id');

		$this->datatables->edit_column('t', '$1', 'test_get_link_by_id(test_id)');
		$this->datatables->edit_column('name', '$1', 'testcat_get_link_by_id(id)');
		$this->datatables->edit_column('id', '$1', 'testcat_actions(id)');

		$this->datatables->unset_column('test_id');

		echo $this->datatables->generate();
	}

	public function table_roots($test_id = NULL)
	{
		$this->datatables->where('parent_id', NULL);
		if ($test_id)
		{
			$this->datatables->where('test_id', $test_id);
			$this->datatables->unset_column('t');
		}
		$this->table();
	}

	public function table_children($testcat_id)
	{
		$this->datatables->where('parent_id', $testcat_id);
		$this->datatables->unset_column('t');
		$this->table();
	}
}
