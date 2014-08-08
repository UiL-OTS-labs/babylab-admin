<?php
class Call extends CI_Controller
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
		create_call_table();
		$data['ajax_source'] = 'call/table/';
		$data['sort_column'] = 5;	// Sort on timestart
		$data['page_title'] = lang('calls');

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/** Deletes the specified call, and returns to previous page */
	public function delete($call_id)
	{
		$this->callModel->delete_call($call_id);
		flashdata(lang('call_deleted'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Other views
	/////////////////////////

	/** Gets all calls for a user. */
	public function user($user_id)
	{
		if (!is_admin() && !correct_user($user_id)) return;

		create_call_table(NULL, TRUE);
		$data['ajax_source'] = 'call/table_by_user/' . $user_id;
		$data['sort_column'] = 5;	// Sort on timestart
		$data['page_title'] = lang('calls');

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// Call actions: undo, confirm, no reply, cancel
	/////////////////////////

	/** Undo: deletes call, releases participation lock and returns to the experiment call page (with message) */
	public function undo($call_id)
	{
		$participation = $this->callModel->get_participation_by_call($call_id);

		$this->callModel->delete_call($call_id);
		$this->participationModel->release_lock($participation->id);

		$participant = $this->participationModel->get_participant_by_participation($participation->id);
		$experiment = $this->participationModel->get_experiment_by_participation($participation->id);
		flashdata(sprintf(lang('part_cancel_call'), name($participant), $experiment->name));

		redirect('/participant/find/' . $experiment->id, 'refresh');
	}

	/** Confirm: confirms the participation with a scheduled date */
	public function confirm($call_id)
	{
		$participation = $this->callModel->get_participation_by_call($call_id);
		$participant = $this->participationModel->get_participant_by_participation($participation->id);
		$experiment = $this->participationModel->get_experiment_by_participation($participation->id);

		$this->form_validation->set_rules('appointment', lang('appointment'), 'trim|required');

		// Run validation
		if (!$this->form_validation->run())
		{
			// If not succeeded, show form again with error messages
			flashdata(validation_errors(), FALSE);
			redirect($this->agent->referrer(), 'refresh');
		}
		else
		{
			// If succeeded, insert data into database
			$appointment = input_datetime($this->input->post('appointment'));
			$this->callModel->end_call($call_id, CallStatus::Confirmed);
			$this->participationModel->confirm($participation->id, $appointment);
			$flashdata = '';
			$invites = $this->create_test_invitations($participant);
			$flashdata .= br() . $invites[0];
			$flashdata .= br() . $this->send_confirmation_email($participation->id, $invites[1]);
			$this->participationModel->release_lock($participation->id);

			flashdata(sprintf(lang('part_confirmed'), name($participant), $experiment->name) . $flashdata);

			redirect('/participant/find/' . $experiment->id, 'refresh');
		}
	}

	/** No-reply: adds a no-reply to the participation, possibly with a message (e.g. voicemail/e-mail) */
	public function no_reply($call_id)
	{
		$participation = $this->callModel->get_participation_by_call($call_id);
		$participant = $this->participationModel->get_participant_by_participation($participation->id);
		$experiment = $this->participationModel->get_experiment_by_participation($participation->id);

		$this->form_validation->set_rules('message', lang('message'), 'required');

		// Run validation
		if (!$this->form_validation->run())
		{
			// If not succeeded, show form again with error messages
			flashdata(validation_errors(), FALSE);
			redirect($this->agent->referrer(), 'refresh');
		}
		else
		{
			// If succeeded, insert data into database
			$message = $this->input->post('message');
			$message = $message === 'none' ? CallStatus::NoReply : $message;

			$flashdata = '';

			$this->callModel->end_call($call_id, $message);
			$this->participationModel->no_reply($participation->id);
			$this->participationModel->release_lock($participation->id);

			$participation = $this->participationModel->get_participation_by_id($participation->id);
			if ($participation->nrcalls == SEND_REQUEST_AFTER_CALLS)
			{
				$flashdata = br() . $this->send_request_participation_email($participation->id);
				$this->callModel->update_call($call_id, CallStatus::Email);
			}

			flashdata(sprintf(lang('part_no_reply'), name($participant), $experiment->name) . $flashdata);

			redirect('/participant/find/' . $experiment->id, 'refresh');
		}
	}

	/** Cancel: cancels the participation to the experiment */
	public function cancel($call_id)
	{
		$participation = $this->callModel->get_participation_by_call($call_id);
		$participant = $this->participationModel->get_participant_by_participation($participation->id);

		// Add (possible) comment
		$comment = $this->post_comment($participant->id);
		if (!empty($comment)) $this->commentModel->add_comment($comment);

		// End the call
		$this->callModel->end_call($call_id, CallStatus::Cancelled);
		$this->participationModel->cancel($participation->id, TRUE);
		$this->participationModel->release_lock($participation->id);

		$participant = $this->participationModel->get_participant_by_participation($participation->id);
		$experiment = $this->participationModel->get_experiment_by_participation($participation->id);
		flashdata(sprintf(lang('part_cancelled'), name($participant), $experiment->name));

		redirect('/participant/find/' . $experiment->id, 'refresh');
	}

	/////////////////////////
	// Mails
	/////////////////////////

	/** Send confirmation e-mail */
	private function send_confirmation_email($participation_id, $testinvites)
	{
		$participation = $this->participationModel->get_participation_by_id($participation_id);
		$participant = $this->participationModel->get_participant_by_participation($participation_id);
		$experiment = $this->participationModel->get_experiment_by_participation($participation_id);
		$testinvite = $testinvites[0]; // TODO: this is ugly. there should be only one (Anamnese), but we don't check for that.
		
		$message = email_replace('mail/confirmation', $participant, $participation, $experiment, $testinvite);

		$this->email->clear();
		$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
		$this->email->to(EMAIL_DEV_MODE ? TO_EMAIL_OVERRIDE : $participant->email);
		$this->email->subject('Babylab Utrecht: Bevestiging van uw afspraak');
		$this->email->message($message);
		$this->email->send();

		return sprintf(lang('confirmation_sent'), $participant->email);
	}

	/** Send request for participation e-mail */
	private function send_request_participation_email($participation_id)
	{
		$participation = $this->participationModel->get_participation_by_id($participation_id);
		$participant = $this->participationModel->get_participant_by_participation($participation_id);
		$experiment = $this->participationModel->get_experiment_by_participation($participation_id);

		$message = email_replace('mail/request_participation', $participant, $participation, $experiment);

		$this->email->clear();
		$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
		$this->email->to(EMAIL_DEV_MODE ? TO_EMAIL_OVERRIDE : $participant->email);
		$this->email->subject('Babylab Utrecht: Verzoek tot deelname aan onderzoek');
		$this->email->message($message);
		$this->email->send();

		return sprintf(lang('request_participation_sent'), $participant->email);
	}

	/** Create test invitations (based on number of participations), 
	 * but don't send mail (should be in confirmation e-mail already) */
	private function create_test_invitations($participant)
	{
		$testinvites = $this->testInviteModel->create_testinvites_by_participation($participant);

		$flashdata = '';
		foreach ($testinvites as $testinvite)
		{
			$test = $this->testInviteModel->get_test_by_testinvite($testinvite);
			$flashdata .= sprintf(lang('testinvite_added'), name($participant), $test->name);
		}

		return array($flashdata, $testinvites);
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Posts the data for a comment */
	private function post_comment($participant_id)
	{
		$comment = $this->input->post('comment');
		if (empty($comment)) return NULL;

		$user_id = current_user_id();
		if (empty($user_id)) $user_id = system_user_id();

		return array(
				'body'				=> $comment,
				'participant_id' 	=> $participant_id,
				'user_id'		 	=> $user_id
		);
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('username, CONCAT(firstname, lastname) AS p, experiment.name AS e,
			call.status AS status, nr, timestart, timeend,
			call.id AS id, participant_id, experiment_id, user_id', FALSE);
		$this->datatables->from('call');
		$this->datatables->join('participation', 'participation.id = call.participation_id');
		$this->datatables->join('participant', 'participant.id = participation.participant_id');
		$this->datatables->join('experiment', 'experiment.id = participation.experiment_id');
		$this->datatables->join('user', 'user.id = call.user_id');

		$this->datatables->edit_column('username', '$1', 'user_get_link_by_id(user_id)');
		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(participant_id)');
		$this->datatables->edit_column('e', '$1', 'experiment_get_link_by_id(experiment_id)');
		$this->datatables->edit_column('status', '$1', 'lang(status)');
		$this->datatables->edit_column('timestart', '$1', 'output_datetime(timestart)');
		$this->datatables->edit_column('timeend', '$1', 'output_datetime(timeend)');
		$this->datatables->edit_column('id', '$1', 'call_actions(id)');

		$this->datatables->unset_column('participant_id');
		$this->datatables->unset_column('experiment_id');
		$this->datatables->unset_column('user_id');

		echo $this->datatables->generate();
	}

	public function table_by_user($user_id)
	{
		$this->datatables->where('user_id', $user_id);
		$this->datatables->unset_column('username');
		$this->table();
	}

	public function table_by_participation($participation_id)
	{
		$this->datatables->where('participation_id', $participation_id);
		$this->table();
	}
}
