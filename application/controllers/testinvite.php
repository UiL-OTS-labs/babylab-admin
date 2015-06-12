<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TestInvite extends CI_Controller
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
	public function index($needs_manual_reminder = FALSE)
	{
		$add_url = array('url' => 'testinvite/add', 'title' => lang('add_testinvite'));
		$manual_reminder_url = array('url' => 'testinvite/index/1', 'title' => lang('needs_manual_reminder'));
		$show_all_url = array('url' => 'testinvite/index', 'title' => lang('show_all_testinvites'));
		$filter_url = $needs_manual_reminder ? $show_all_url : $manual_reminder_url;

		create_testinvite_table();
		$data['ajax_source'] = 'testinvite/table/' . $needs_manual_reminder;
		$data['page_title'] = lang('testinvites');
		$data['action_urls'] = array($add_url, $filter_url);
		$data['sort_column'] = 3; // Sort on date sent, descending
		$data['sort_order'] = 'desc'; 

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the add testinvite page */
	public function add()
	{
		$testsurveys = $this->testSurveyModel->get_all_testsurveys();
		$participants = $this->participantModel->get_all_participants(TRUE);
		
		$data['testsurveys'] = testsurvey_options($testsurveys);
		$data['participants'] = participant_options($participants);

		$data['page_title'] = lang('add_testinvite');
		$data['action'] = 'testinvite/add_submit/';
		$data = add_fields($data, 'testinvite');

		$this->load->view('templates/header', $data);
		$this->load->view('testinvite_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the addition of a testinvite */
	public function add_submit()
	{
		// Run validation
		if (!$this->validate_testinvite())
		{
			// If not succeeded, show form again with error messages
			$this->add();
		}
		else
		{
			// If succeeded, insert data into database
			$testsurvey_id = $this->input->post('testsurvey');
			$participant_id = $this->input->post('participant');
			$concept = $this->input->post('concept');
			$this->invite($testsurvey_id, $participant_id, $concept);
		}
	}

	/** Finishes the invitation */
	public function invite($testsurvey_id, $participant_id, $concept)
	{
		$testinvite = $this->testInviteModel->create_testinvite($testsurvey_id, $participant_id);
		$testsurvey = $this->testSurveyModel->get_testsurvey_by_id($testsurvey_id);
		$participant = $this->participantModel->get_participant_by_id($participant_id);

		// Create the token in LimeSurvey (if we're on production)
		if (!SURVEY_DEV_MODE)
		{
			$this->load->model('surveyModel');
			$this->surveyModel->create_token($participant, $testsurvey->limesurvey_id, $testinvite->token);
		}

		// Email to participant and return to overview
		$flashdata = email_testinvite($participant, $testinvite, FALSE, $concept);
		flashdata($flashdata);
		redirect('/testinvite/', 'refresh');
	}

	/** Opens a view to send a manual reminder */
	public function manual_reminder($testinvite_id)
	{
		$testinvite = $this->testInviteModel->get_testinvite_by_id($testinvite_id);
		$participant = $this->testInviteModel->get_participant_by_testinvite($testinvite);
		$testsurvey = $this->testInviteModel->get_testsurvey_by_testinvite($testinvite);
		
		$data['testinvite'] = $testinvite;
		$data['testsurvey'] = $testsurvey;
		$data['participant'] = $participant;
		$data['page_title'] = lang('manual_reminder');

		$this->load->view('templates/header', $data);
		$this->load->view('testinvite_reminder_view', $data);
		$this->load->view('templates/footer');
	}

	/** Resend the reminder */
	public function manual_reminder_submit($testinvite_id)
	{
		$testinvite = $this->testInviteModel->get_testinvite_by_id($testinvite_id);
		$participant = $this->testInviteModel->get_participant_by_testinvite($testinvite);
		$testsurvey = $this->testInviteModel->get_testsurvey_by_testinvite($testinvite);
		$test = $this->testInviteModel->get_test_by_testinvite($testinvite);
		$template = $this->testTemplateModel->get_testtemplate_by_test($test->id, L::Dutch);

		// Email to participant
		$message = email_replace($template->template . '_reminder', $participant, NULL, NULL, $testinvite);

		$this->email->clear();
		$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
		$this->email->to(in_development() ? TO_EMAIL_OVERRIDE : $participant->email);
		$this->email->subject('Babylab Utrecht: Herinnering uitnodiging voor vragenlijst');
		$this->email->message($message);
		$this->email->send();

		$this->testInviteModel->set_reminded($testinvite->id);

		// Send reminder
		flashdata(sprintf(lang('manual_reminder_sent'), testsurvey_name($testsurvey), $participant->email));
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
		$this->form_validation->set_rules('participant', lang('participant'), 'callback_not_zero');

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
		$participant_id = $this->input->post('participant');
		$testinvite = $this->testInviteModel->get_testinvite_by_testsurvey_participant($testsurvey_id, $participant_id);

		if ($testinvite)
		{
			$participant = $this->participantModel->get_participant_by_id($participant_id);
			$testsurvey = $this->testSurveyModel->get_testsurvey_by_id($testsurvey_id);
			$this->form_validation->set_message('testinvite_exists', sprintf(lang('testinvite_already_exists'), name($participant), testsurvey_name($testsurvey)));
			return FALSE;
		}
		return TRUE;
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table($needs_manual_reminder = FALSE)
	{
		$this->datatables->select('test.name AS t, CONCAT(firstname, " ", lastname) AS p,
			token, datesent, datecompleted, datereminder, testinvite.id AS id, 
			testsurvey.id AS testsurvey_id, participant_id', FALSE);
		$this->datatables->from('testinvite');
		$this->datatables->join('participant', 'participant.id = testinvite.participant_id');
		$this->datatables->join('testsurvey', 'testsurvey.id = testinvite.testsurvey_id');
		$this->datatables->join('test', 'test.id = testsurvey.test_id');

		$this->datatables->edit_column('t', '$1', 'testsurvey_get_link_by_id(testsurvey_id)');
		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(participant_id)');
		$this->datatables->edit_column('datesent', '$1', 'output_date(datesent)');
		$this->datatables->edit_column('datecompleted', '$1', 'output_date(datecompleted)');
		$this->datatables->edit_column('datereminder', '$1', 'output_date(datereminder)');
		$this->datatables->edit_column('id', '$1', 'testinvite_actions(id)');

		if ($needs_manual_reminder)
		{
			$this->datatables->where('datecompleted', NULL);
			$this->datatables->where('datereminder < ', input_datetime('-1 week'));
		}

		$this->datatables->unset_column('testsurvey_id');
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

	public function table_by_participant($participant_id)
	{
		$this->datatables->where('participant.id', $participant_id);
		$this->datatables->unset_column('p');
		$this->table();
	}

	public function table_by_experiment($experiment_id)
	{
		$participant_ids = get_object_ids($this->experimentModel->get_participants_by_experiment($experiment_id, TRUE));
		if (empty($participant_ids)) $this->datatables->where('participant.id', 0)	; // no participants then
		else $this->datatables->where('participant.id IN (' . implode(',', $participant_ids) . ')');

		$this->table();
	}
}
