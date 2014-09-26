<?php
class Language extends CI_Controller
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
		create_language_table();
		$data['ajax_source'] = 'language/table/';
		$data['page_title'] = lang('languages');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/** Adds a language for the specified participant */
	public function add_submit($participant_id)
	{
		// Run validation
		if (!$this->validate_language())
		{
			// Show form again with error messages
			flashdata(validation_errors(), FALSE, 'language_message');
			redirect($this->agent->referrer(), 'refresh');
		}
		else
		{
			// If succeeded, insert data into database
			$language = $this->post_language($participant_id);
			$this->languageModel->add_language($language);

			flashdata(lang('language_added'), TRUE, 'language_message');
			redirect($this->agent->referrer(), 'refresh');
		}
	}

	/** Deletes the specified language, and returns to previous page */
	public function delete($language_id)
	{
		$this->languageModel->delete_language($language_id);
		flashdata(lang('language_deleted'), TRUE, 'language_message');
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates a language */
	private function validate_language()
	{
		$this->form_validation->set_rules('language', lang('language'), 'trim|required');

		return $this->form_validation->run();
	}

	/** Posts the data for a language */
	private function post_language($participant_id)
	{
		return array(
				'body'				=> $this->input->post('language'),
				'participant_id' 	=> $participant_id
		);
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('CONCAT(firstname, " ", lastname) AS p, language, percentage,
			language.id AS id, participant_id', FALSE);
		$this->datatables->from('language');
		$this->datatables->join('participant', 'participant.id = language.participant_id');

		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(participant_id)');
		$this->datatables->edit_column('id', '$1', 'language_actions(id)');

		$this->datatables->unset_column('participant_id');

		echo $this->datatables->generate();
	}

	public function table_by_participant($participant_id)
	{
		$this->datatables->where('participant_id', $participant_id);
		$this->table();
	}
}
