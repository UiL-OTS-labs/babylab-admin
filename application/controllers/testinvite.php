<?php
class TestInvite extends CI_Controller
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
		$add_url = array('url' => 'testinvite/add', 'title' => lang('add_testinvite'));

		create_testinvite_table();
		$data['ajax_source'] = 'testinvite/table/';
		$data['page_title'] = lang('testinvites');
		$data['action_urls'] = array($add_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data);
		$this->load->view('templates/footer');
	}
	
	/** Specifies the contents of the add testinvite page */
	public function add($testsurvey_id = 0)
	{
		$data['testsurveys'] = $this->testSurveyModel->get_all_testsurveys();
		$data['participants'] = $this->participantModel->get_all_participants(TRUE);
		
		$data['page_title'] = lang('add_testinvite');
		$data['new_testinvite'] = TRUE;
		$data['action'] = 'testinvite/add_submit/';
		$data = add_fields($data, 'testinvite');
		
		$data['testsurvey_id'] = $testsurvey_id;
	
		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('testinvite_edit_view', $data);
		$this->load->view('templates/footer');
	}
	
	/** Submits the addition of a testinvite */
	public function add_submit()
	{
		// Run validation
		if (!$this->validate_testinvite()) 
		{
			// If not succeeded, show form again with error messages
			$this->add($this->input->post('testsurvey_id'));
		}
		else 
		{
			// If succeeded, insert data into database
			$testsurvey_id = $this->input->post('testsurvey');
			$participant_id = $this->input->post('participant_id');
			$this->invite($testsurvey_id, $participant_id);
		}
	}
	
	/** Finishes the invitation */
	public function invite($testsurvey_id, $participant_id)
	{
		$testinvite = $this->testInviteModel->create_testinvite($testsurvey_id, $participant_id);
		
		$testsurvey = $this->testSurveyModel->get_testsurvey_by_id($testsurvey_id);
		$participant = $this->participantModel->get_participant_by_id($participant_id);

		// Create the token in LimeSurvey
		$this->load->model('surveyModel');
		$this->surveyModel->create_token($participant, $testsurvey->limesurvey_id, $testinvite->token);
		
		// Email to participant
		$flashdata = email_testinvite($participant, $testinvite);
		
		// Return to list
		flashdata($flashdata);
		redirect('/testinvite/', 'refresh');
	}
	
	/** Deletes the specified testinvite, and returns to previous page */
	public function delete($testinvite_id)
	{
		$this->testInviteModel->delete_testinvite($testinvite_id);
		flashdata(lang('testinvite_deleted'));
		redirect($this->agent->referrer(), 'refresh');
	}
	
	/////////////////////////
	// Form handling
	/////////////////////////
	
	/** Validates a testinvite */
	private function validate_testinvite()
	{
		$this->form_validation->set_rules('testsurvey', lang('testsurvey'), 'callback_not_zero|callback_testinvite_exists');
		$this->form_validation->set_rules('participant_id', lang('participant'), 'callback_not_zero');
		
		return $this->form_validation->run();
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
	
	/** Checks whether the given parameter is higher than 0 */
	public function testinvite_exists($testsurvey_id)
	{
		$participant_id = $this->input->post('participant_id');
		$testinvite = $this->testInviteModel->get_testinvite_by_testsurvey_participant($testsurvey_id, $participant_id);
		
		if (!empty($testinvite))
		{
			$participant = $this->participantModel->get_participant_by_id($participant_id);
			$test = $this->testInviteModel->get_test_by_testinvite($testinvite);
			$this->form_validation->set_message('testinvite_exists', sprintf(lang('testinvite_already_exists'), name($participant), $test->name));
			return FALSE;
		}
		return TRUE;
	}
	
	/////////////////////////
	// AJAX
	/////////////////////////
	
	/** Checks whether the given parameter is higher than 0 */
	public function filter_participants()
	{
		$term = $this->input->get('term');
		$participants = $this->participantModel->find_participants_by_name($term);
		
		echo json_encode($participants);
	}	
	
	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('test.name AS t, CONCAT(firstname, lastname) AS p, 
			token, datesent, datecompleted, testinvite.id AS id, 
			testsurvey.id AS testsurvey_id, participant_id', FALSE);
		$this->datatables->from('testinvite');
		$this->datatables->join('participant', 'participant.id = testinvite.participant_id');
		$this->datatables->join('testsurvey', 'testsurvey.id = testinvite.testsurvey_id');
		$this->datatables->join('test', 'test.id = testsurvey.test_id');

		$this->datatables->edit_column('t', '$1', 'testsurvey_get_link_by_id(testsurvey_id)');
		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(participant_id)');
		$this->datatables->edit_column('datesent', '$1', 'output_date(datesent)');
		$this->datatables->edit_column('datecompleted', '$1', 'output_date(datecompleted)');
		$this->datatables->edit_column('id', '$1', 'testinvite_actions(id)');
		
		$this->datatables->unset_column('test_id');
		$this->datatables->unset_column('participant_id');

		echo $this->datatables->generate();
	}
	
	public function table_by_test($test_id) 
	{
		$this->datatables->where('test.id', $test_id);
		$this->datatables->unset_column('t');
		$this->table();
	}
	
	public function table_by_testsurvey($testsurvey_id) 
	{
		$this->datatables->where('testsurvey.id', $testsurvey_id);
		$this->datatables->unset_column('t');
		$this->table();
	}
}
