<?php
class Relation extends CI_Controller
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
		create_relation_table();
		$data['ajax_source'] = 'relation/table/';
		$data['page_title'] = lang('relations');

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Leader);
		$this->load->view('templates/footer');
	}

	/** Deletes the given relation */
	public function delete($relation_id)
	{
		$this->relationModel->delete_relation($relation_id);
		flashdata(lang('relation_deleted'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('experiment_id, relation, rel_exp_id, id', FALSE);
		$this->datatables->from('relation');

		$this->datatables->edit_column('experiment_id', '$1', 'experiment_get_link_by_id(experiment_id)');
		$this->datatables->edit_column('relation', '$1', 'lang(relation)');
		$this->datatables->edit_column('rel_exp_id', '$1', 'experiment_get_link_by_id(rel_exp_id)');
		$this->datatables->edit_column('id', '$1', 'relation_actions(id)');

		echo $this->datatables->generate();
	}

	public function table_by_experiment($experiment_id)
	{
		$this->datatables->where('experiment_id = ' . $experiment_id . ' OR rel_exp_id = ' . $experiment_id);
		$this->table();
	}
}
