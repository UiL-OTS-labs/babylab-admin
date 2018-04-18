<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Testtemplate extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());

		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index()
	{
		$add_url = array('url' => 'testtemplate/add', 'title' => lang('add_testtemplate'));

		create_testtemplate_table();
		$data['ajax_source'] = 'testtemplate/table/';
		$data['page_title'] = lang('testtemplates');
		$data['action_urls'] = array($add_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::ADMIN);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the add testtemplate page */
	public function add($test_id = 0)
	{
		$data['tests'] = $this->testModel->get_all_tests();

		$data['page_title'] = lang('add_testtemplate');
		$data['new_testtemplate'] = TRUE;
		$data['action'] = 'testtemplate/add_submit/';
		$data = add_fields($data, 'testtemplate');

		$data['test_id'] = $test_id;

		$this->load->view('templates/header', $data);
		$this->load->view('testtemplate_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the addition of a testtemplate */
	public function add_submit()
	{
		// Run validation
		if (!$this->validate_testtemplate(TRUE))
		{
			// If not succeeded, show form again with error messages
			$this->add($this->input->post('test'));
		}
		else
		{
			// If succeeded, insert data into database
			$testtemplate = $this->post_testtemplate(TRUE);
			$testtemplate_id = $this->testTemplateModel->add_testtemplate($testtemplate);

			$t = $this->testTemplateModel->get_testtemplate_by_id($testtemplate_id);
			$test = $this->testTemplateModel->get_test_by_testtemplate($t);
			flashdata(sprintf(lang('testtemplate_added'), $test->name));
			redirect('/testtemplate/', 'refresh');
		}
	}

	/** Specifies the contents of the edit testtemplate page */
	public function edit($testtemplate_id)
	{
		$data['tests'] = $this->testModel->get_all_tests();

		$testtemplate = $this->testTemplateModel->get_testtemplate_by_id($testtemplate_id);
		$test = $this->testTemplateModel->get_test_by_testtemplate($testtemplate);
		$data['test'] = $test;

		$data['page_title'] = sprintf(lang('edit_testtemplate'), $test->name);
		$data['new_testtemplate'] = FALSE;
		$data['action'] = 'testtemplate/edit_submit/' . $testtemplate_id;
		$data = add_fields($data, 'testtemplate', $testtemplate);

		$this->load->view('templates/header', $data);
		$this->load->view('testtemplate_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the edit of a testtemplate */
	public function edit_submit($testtemplate_id)
	{
		// Run validation
		if (!$this->validate_testtemplate(FALSE))
		{
			// If not succeeded, show form again with error messages
			$this->edit($testtemplate_id);
		}
		else
		{
			// If succeeded, update data into database
			$testtemplate = $this->post_testtemplate(FALSE);
			$this->testTemplateModel->update_testtemplate($testtemplate_id, $testtemplate);

			$t = $this->testTemplateModel->get_testtemplate_by_id($testtemplate_id);
			$test = $this->testTemplateModel->get_test_by_testtemplate($t);
			flashdata(sprintf(lang('testtemplate_edited'), $test->name));
			redirect('/testtemplate/', 'refresh');
		}
	}

	/** Deletes the specified testtemplate, and returns to previous page */
	public function delete($testtemplate_id)
	{
		$this->testTemplateModel->delete_testtemplate($testtemplate_id);
		flashdata(lang('testtemplate_deleted'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates a testtemplate */
	private function validate_testtemplate($new_testtemplate)
	{
		if ($new_testtemplate)
		{
			$this->form_validation->set_rules('test', lang('test'), 'callback_not_zero');
		}

		$this->form_validation->set_rules('template', lang('template'), 'trim|required');

		return $this->form_validation->run();
	}

	/** Posts the data for a testtemplate */
	private function post_testtemplate($new_testtemplate)
	{
		$testtemplate = array(
			'test_id' => $this->input->post('test'),
			'template' => $this->input->post('template'),
			'language' => L::DUTCH, // TODO: actually use this field
		);

		if (!$new_testtemplate)
		{
			unset($testtemplate['test_id']);
		}

		return $testtemplate;
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

	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('test.name AS t, language, template,
			testtemplate.id AS id, test_id', FALSE);
		$this->datatables->from('testtemplate');
		$this->datatables->join('test', 'test.id = testtemplate.test_id');

		$this->datatables->edit_column('t', '$1', 'test_get_link_by_id(test_id)');
		$this->datatables->edit_column('language', '$1', 'lang(language)');
		$this->datatables->edit_column('id', '$1', 'testtemplate_actions(id)');

		$this->datatables->unset_column('test_id');
		$this->datatables->unset_column('whennr');

		echo $this->datatables->generate();
	}

	public function table_by_test($test_id)
	{
		$this->datatables->where('test.id', $test_id);
		$this->table();
	}

}
