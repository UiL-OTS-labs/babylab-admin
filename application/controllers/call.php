<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Call extends CI_Controller
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

	/**
	 * Specifies the contents of the default page.
	 * @return void
	 */
	public function index()
	{
		create_call_table();
		$data['ajax_source'] = 'call/table/';
		$data['sort_column'] = 5;	// Sort on timestart
		$data['page_title'] = lang('calls');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Deletes the specified call, and returns to previous page
	 * @param integer $call_id 
	 * @return void
	 */
	public function delete($call_id)
	{
		$this->callModel->delete_call($call_id);
		flashdata(lang('call_deleted'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Other views
	/////////////////////////

	/**
	 * Gets all calls for a user. 
	 * @param integer $user_id 
	 * @return void
	 */
	public function user($user_id)
	{
		if (!is_admin() && !correct_user($user_id)) return;

		create_call_table(NULL, FALSE);
		$data['ajax_source'] = 'call/table_by_user/' . $user_id;
		$data['sort_column'] = 5;	// Sort on timestart
		$data['page_title'] = lang('calls');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// Call actions: undo, confirm, no reply, cancel, take over
	/////////////////////////

	/** Undo: deletes call, releases participation lock and returns to the experiment call page (with message) */
	public function undo($call_id)
	{
		$this->check_integrity($call_id);
		
		$participation = $this->callModel->get_participation_by_call($call_id);

		$this->callModel->delete_call($call_id);
		$this->participationModel->release_lock($participation->id);

		$participant = $this->participationModel->get_participant_by_participation($participation->id);
		$experiment = $this->participationModel->get_experiment_by_participation($participation->id);
		flashdata(sprintf(lang('part_cancel_call'), name($participant), $experiment->name));

		redirect('/participant/find/' . $experiment->id, 'refresh');
	}

	/** Confirm: confirms the participation with a scheduled date */
	public function confirm($call_id, $leader = NULL, $appointment = NULL)
	{
		$this->check_integrity($call_id);
		
		$participation = $this->callModel->get_participation_by_call($call_id);
		$participant = $this->participationModel->get_participant_by_participation($participation->id);
		$experiment = $this->participationModel->get_experiment_by_participation($participation->id);

		$this->form_validation->set_rules('appointment', lang('appointment'), 
                'trim|required|callback_check_closings[' . $experiment->location_id . ']');
		
		// Run validation
		if (!$this->form_validation->run() && $appointment == NULL)
		{
			// If not succeeded, show form again with error messages
			flashdata(validation_errors(), FALSE);
			redirect($this->agent->referrer(), 'refresh');
		}
		else
		{
			$ad_hoc = FALSE;
			$leader_id = $leader ? $leader : $this->input->post('leader');

			// If succeeded, insert data into database
			if (!$appointment)
			{
				// This is a confirmation of a call, so post the appointment and format it for MySQL.
				$appointment = input_datetime($this->input->post('appointment'));
			}
			else 
			{
				// This is an ad-hoc participation send via url as Unix timestamp, so directly convert to MySQL date.
				$appointment = date('Y-m-d H:i:s', $appointment);
				// Set a flag to not send a confirmation email / return to participations overview
				$ad_hoc = TRUE;
			}

			// End the call, confirm the participation
			$this->callModel->end_call($call_id, CallStatus::Confirmed);
			$this->participationModel->confirm($participation->id, $appointment, $leader_id);
			$this->participationModel->release_lock($participation->id);

			// Fetch the participant's email (or the concept e-mail)
			$flashdata = '';
			$email = $this->input->post('concept') ? TO_EMAIL_OVERRIDE : $participant->email;

			// Send the anamnese (or not, if checkbox is set)
			$testinvite = NULL;
			if ($this->input->post('send_anamnese'))
			{
				$invites = $this->create_test_invitations($participant);
				$flashdata .= br() . $invites[0];
				$testinvites = $invites[1];
				$testinvite = $testinvites[0]; // TODO: this is ugly. there should be only one (Anamnese), but we don't check for that.
			}

			// If there's a combination appointment made, create that participation as well, plus a confirmation e-mail 
			if ($this->input->post('comb_appointment'))
			{
				$comb_experiment = $this->experimentModel->get_experiment_by_id($this->input->post('comb_exp'));
				$comb_leader = $this->input->post('comb_leader');
				$comb_participation_id = $this->participationModel->create_participation($comb_experiment, $participant);
				$comb_appointment = input_datetime($this->input->post('comb_appointment'));
				$this->participationModel->confirm($comb_participation_id, $comb_appointment, $comb_leader);
				$flashdata .= br() . $this->send_confirmation_email($participation->id, $testinvite, $email, $comb_experiment);
			}
			// Else we can send a simple confirmation e-mail
			else 
			{
				if (!$ad_hoc)
				{
					$flashdata .= br() . $this->send_confirmation_email($participation->id, $testinvite, $email);
				}
			}

			// If we send a concept, add that to the confirmation message
			if ($this->input->post('concept')) 
			{
				$flashdata .= br(2) . sprintf(lang('concept_send'), $email);
			}

			// Return to the find participants page with a success message
			flashdata(sprintf(lang('part_confirmed'), name($participant), $experiment->name) . $flashdata);
			redirect($ad_hoc ? '/participation/' : '/participant/find/' . $experiment->id, 'refresh');
		}
	}

	/** No-reply: adds a no-reply to the participation, possibly with a message (e.g. voicemail/e-mail) */
	public function no_reply($call_id)
	{
		$this->check_integrity($call_id);
		
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
		$this->check_integrity($call_id);
		
		$participation = $this->callModel->get_participation_by_call($call_id);
		$participant = $this->participationModel->get_participant_by_participation($participation->id);
		$experiment = $this->participationModel->get_experiment_by_participation($participation->id);

		// Add (possible) comment
		$comment = $this->post_comment($participant->id);
		if ($comment) 
        {
            $this->commentModel->add_comment($comment);
        }

		// End the call
		$this->callModel->end_call($call_id, CallStatus::Cancelled);
		$this->participationModel->cancel($participation->id, TRUE);
		$this->participationModel->release_lock($participation->id);

		// Deactivate the participant
		if ($this->input->post('never_again'))
		{
			$this->participantModel->deactivate($participant->id, DeactivateReason::DuringCall);
			flashdata(sprintf(lang('part_cancelled_complete'), name($participant), $experiment->name, name($participant)));
		}
		else
		{
			flashdata(sprintf(lang('part_cancelled'), name($participant), $experiment->name));
		}

		redirect('/participant/find/' . $experiment->id, 'refresh');
	}
	
	/** Take over: takes over a call (when someone else is calling, but didn't finish) */
	public function take_over($call_id)
	{
		$this->check_integrity($call_id, TRUE);
		
		$participation = $this->callModel->get_participation_by_call($call_id);

		$this->callModel->delete_call($call_id);
		$this->participationModel->release_lock($participation->id);

		redirect('participation/call/' . $participation->participant_id . '/' . $participation->experiment_id, 'refresh');
	}
	
	/**
	 * 
	 * Check integrity of a call: 
	 * - the user should be logged in (by default for each method, so we don't have to check here)
	 * - the call should exist
	 * - the user should be the caller of this call (unless it's a take-over)
	 * 
	 * @param integer $call_id the ID of the call
	 */
	private function check_integrity($call_id, $take_over = FALSE)
	{
		$call = $this->callModel->get_call_by_id($call_id);
		if (empty($call)) 
		{
			show_error("Call does not exist. It might have been taken over.");
		}
		if ($call->user_id != current_user_id() && !$take_over)
		{
			show_error("You are not the caller for this call.");
		}
	}

	/////////////////////////
	// Mails
	/////////////////////////

	/** Send confirmation e-mail */
	private function send_confirmation_email($participation_id, $testinvite, $email, $comb_exp = NULL)
	{
		$participation = $this->participationModel->get_participation_by_id($participation_id);
		$participant = $this->participationModel->get_participant_by_participation($participation_id);
		$experiment = $this->participationModel->get_experiment_by_participation($participation_id);
		$leader_emails = $this->leaderModel->get_leader_emails_by_experiment($experiment->id);
		
		$message = email_replace('mail/confirmation', $participant, $participation, $experiment, $testinvite, $comb_exp);

		$this->email->clear();
		$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
		$this->email->to(in_development() ? TO_EMAIL_OVERRIDE : $email);
		$this->email->bcc(in_development() ? TO_EMAIL_OVERRIDE : $leader_emails);
		$this->email->subject('Babylab Utrecht: Bevestiging van uw afspraak');
		$this->email->message($message);

		// Add attachment for experiment
		if ($experiment->attachment)
		{
			if (file_exists('uploads/' . $experiment->attachment))
			{
				$this->email->attach('uploads/' . $experiment->attachment);
			}
		}
		// Add informed consent
		if ($experiment->informedconsent)
		{
			if (file_exists('uploads/' . $experiment->informedconsent))
			{
				$this->email->attach('uploads/' . $experiment->informedconsent);
			}
		}
		// Add attachment (only for combination experiments)
		if ($comb_exp && $comb_exp->attachment) 
		{
			$relation = $this->relationModel->get_relation_by_experiments($experiment->id, $comb_exp->id);
			if ($relation->relation === RelationType::Combination && file_exists('uploads/' . $comb_exp->attachment))
			{
				$this->email->attach('uploads/' . $comb_exp->attachment);
			}
		}

		$this->email->send();

		return sprintf(lang('confirmation_sent'), in_development() ? TO_EMAIL_OVERRIDE : $email);
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
		$this->email->to(in_development() ? TO_EMAIL_OVERRIDE : $participant->email);
		$this->email->subject('Babylab Utrecht: Verzoek tot deelname aan onderzoek');
		$this->email->message($message);
		if ($experiment->attachment)
		{
			if (file_exists('uploads/' . $experiment->attachment))
			{
				$this->email->attach('uploads/' . $experiment->attachment);
			}
		}
		$this->email->send();

		return sprintf(lang('request_participation_sent'), in_development() ? TO_EMAIL_OVERRIDE : $participant->email);
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
			$flashdata .= sprintf(lang('testinvite_added_nomail'), name($participant), $test->name);
		}

		return array($flashdata, $testinvites);
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/**
	 * Posts the data for a comment for a participant
	 * @param integer $participant_id 
	 * @return array
	 */
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
    // Callbacks
    /////////////////////////

    /** Checks whether the given date is within bounds of an existing closing for this location */
    public function check_closings($date, $location_id)
    {
        if ($this->closingModel->within_bounds(input_datetime($date), $location_id))
        {
            $this->form_validation->set_message('check_closings', 
                    sprintf(lang('location_closed'), location_name($location_id)));
            return FALSE;
        }
        return TRUE;
    }

	/////////////////////////
	// Table
	/////////////////////////

	/**
	 * Returns the default table
	 * @return JSON
	 */
	public function table()
	{
		$this->datatables->select('username, 
			CONCAT(participant.firstname, " ", participant.lastname) AS p, 
			experiment.name AS e,
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

	/**
	 * Returns the table filtered by user_id
	 * @param integer $user_id 
	 * @return JSON
	 */
	public function table_by_user($user_id)
	{
		$this->datatables->where('user_id', $user_id);
		$this->datatables->unset_column('username');
		$this->table();
	}

	/**
	 * Returns the table filtered by participation
	 * @param integer $participation_id 
	 * @return JSON
	 */
	public function table_by_participation($participation_id)
	{
		$this->datatables->where('participation_id', $participation_id);
		$this->table();
	}
}
