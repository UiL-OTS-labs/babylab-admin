<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Caller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		reset_language(current_language());
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index()
	{
		create_caller_table();
		$data['ajax_source'] = 'caller/table/';
		$data['page_title'] = lang('callers');

		$exp_without_callers = count($this->callerModel->get_experiments_without_callers());
		if ($exp_without_callers > 0) {
			$data['page_info'] = sprintf(lang('exp_without_call'), $exp_without_callers);
		}

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/** Deletes the given caller */
	public function delete($caller_id)
	{
		/*if (!$this->authenticate->authenticate_session('admin'))
		{
			flashdata('Access denied', false);
			redirect($this->agent->referrer(), 'refresh');
		}*/
		
		$this->callerModel->delete_caller($caller_id);
		flashdata(lang('deleted_caller'));
		redirect($this->agent->referrer(), 'refresh');
		
	}
	
	/////////////////////////
	// Other views
	/////////////////////////

	/** Shows all callers for an experiment. */
	public function experiment($experiment_id)
	{
		$experiment = $this->experimentModel->get_experiment_by_id($experiment_id);
		
		if (empty($experiment)) return;
		
		create_caller_table();
		$data['ajax_source'] = 'caller/table_by_experiment/' . $experiment_id;
		$data['page_title'] = sprintf(lang('callers_for_exp'), $experiment->name);
		$data['page_info'] = sprintf(lang('add_callers_exp'), '../../experiment/edit/' . $experiment->id);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}
	
	/////////////////////////
	// Table
	/////////////////////////
	
	/** Creates the table with caller data */
	public function table()
	{
		$this->datatables->select('experiment_id, user_id_caller, id');
		$this->datatables->from('caller');
		
		$this->datatables->edit_column('experiment_id', '$1', 'experiment_get_link_by_id(experiment_id)');
		$this->datatables->edit_column('user_id_caller', '$1', 'user_get_link_by_id(user_id_caller)');
		$this->datatables->edit_column('id', '$1', 'caller_actions(id)');
		
		echo $this->datatables->generate();
	}
	
	public function table_by_experiment($experiment_id) 
	{
		$this->datatables->where('experiment_id', $experiment_id);
		//$this->datatables->unset_column('experiment_id');
		$this->table();
	}
}
