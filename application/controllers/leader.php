<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Leader extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index()
	{
		create_leader_table();
		$data['ajax_source'] = 'leader/table/';
		$data['page_title'] = lang('leaders');

		$exp_without_leaders = count($this->leaderModel->get_experiments_without_leaders());
		if ($exp_without_leaders > 0) {
			$data['page_info'] = sprintf(lang('exp_without_leader'), $exp_without_leaders);
		}

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/** Deletes the given leader */
	public function delete($leader_id)
	{
		$this->leaderModel->delete_leader($leader_id);
		flashdata(lang('deleted_leader'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Other views
	/////////////////////////

	/** Shows all leaders for an experiment. */
	public function experiment($experiment_id)
	{
		$experiment = $this->experimentModel->get_experiment_by_id($experiment_id);

		if (empty($experiment)) return;

		create_leader_table();
		$data['ajax_source'] = 'leader/table_by_experiment/' . $experiment_id;
		$data['page_title'] = sprintf(lang('leaders_for_exp'), $experiment->name);
		$data['page_info'] = sprintf(lang('add_leaders_exp'), '../../experiment/edit/' . $experiment->id);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// Table
	/////////////////////////

	/** Creates the table with leader data */
	public function table()
	{
		$this->datatables->select('experiment_id, user_id_leader, id');
		$this->datatables->from('leader');

		$this->datatables->edit_column('experiment_id', '$1', 'experiment_get_link_by_id(experiment_id)');
		$this->datatables->edit_column('user_id_leader', '$1', 'user_get_link_by_id(user_id_leader)');
		$this->datatables->edit_column('id', '$1', 'leader_actions(id)');

		echo $this->datatables->generate();
	}

	public function table_by_experiment($experiment_id)
	{
		$this->datatables->where('experiment_id', $experiment_id);
		//$this->datatables->unset_column('experiment_id');
		$this->table();
	}
}
