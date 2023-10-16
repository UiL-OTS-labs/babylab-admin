<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Participant extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except(array(
			'register', 'register_submit', 'register_finish',
			'deregister', 'deregister_submit', 'deregister_finish'));
		reset_language(current_language());

		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index()
	{
		$add_url = array('url' => 'participant/add', 'title' => lang('add_participant'));
		$new_url = array('url' => 'participant/registered', 'title' => lang('new_participants'));
		$overview_url = array('url' => 'participant/age_overview', 'title' => lang('age_overview'));
		$graph_url = array('url' => 'participant/graph', 'title' => lang('participant_graph'));

		create_participant_table();
		$data['ajax_source'] = 'participant/table/';
		$data['page_title'] = lang('participants');
		$data['action_urls'] = array($add_url, $new_url, $overview_url, $graph_url);
		$data['hide_columns'] = '9';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/** Shows the page for a single participant */
	public function get($participant_id)
	{
		$participant = $this->participantModel->get_participant_by_id($participant_id);
		$comments = $this->commentModel->get_comments_by_participant($participant_id);
		$impediments = $this->impedimentModel->get_impediments_by_participant($participant_id);
		$participations = $this->participationModel->get_participations_by_participant($participant_id, TRUE);

		$data['participant'] = $participant;
		$data['last_called'] = $this->participantModel->last_called($participant_id);
		$data['last_experiment'] = $this->participantModel->last_experiment($participant_id);
		$data['comment_size'] = count($comments);
		$data['impediment_size'] = count($impediments);
		$data['participation_size'] = count($participations);
		$data['page_title'] = sprintf(lang('data_for_pp'), name($participant));
		$data['verify_languages'] = language_check($participant);
		$data['verify_dyslexia'] = dyslexia_check($participant);
		$data['languagedisorderparents'] = $this->participantModel->get_languagedisorderparents($participant_id);
		$data['speechdisorderparent'] = $this->participantModel->get_speechdisorderparents($participant_id);

		$this->load->view('templates/header', $data);
		$this->load->view('participant_view', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the add participant page */
	public function add($language_array = array())
	{
		$data['page_title'] = lang('add_participant');
		$data['action'] = 'participant/add_submit';
		$data = add_fields($data, 'participant');

		// dob is a bit of a nasty one, as is the languages component...
		$data['dob'] = '';
		$data['comment'] = $this->input->post('comment');
		$data['languages'] = $this->create_language_objects($language_array);
		$data['is_registration'] = FALSE;

		$this->load->view('templates/header', $data);
		$this->load->view('participant_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the addition of a participant */
	public function add_submit()
	{
		// Run validation
		if (!$this->validate_participant(FALSE))
		{
			// If not succeeded, show form again with error messages
			$language_array = $this->create_language_array();
			$this->add($language_array);
		}
		else
		{
			// If succeeded, create the participant
			$participant = $this->post_participant();
			$participant_id = $this->participantModel->add_participant($participant);

			// Add (possible) comment
			$comment = $this->post_comment($participant_id);
			if ($comment) $this->commentModel->add_comment($comment);

			// Create the languages
			$this->create_languages($participant_id);

			// Activate the participant (only on manual creation from the application!)
			$this->participantModel->activate($participant_id);

			// Display success
			$p = $this->participantModel->get_participant_by_id($participant_id);
			flashdata(sprintf(lang('new_pp_added'), name($p)));
			redirect('/participant/', 'refresh');
		}
	}

	/** Specifies the contents of the edit participant page */
	public function edit($participant_id, $language_array = array(), $header = 1)
	{
		$participant = $this->participantModel->get_participant_by_id($participant_id);

		$data['page_title'] = sprintf(lang('edit_participant'), name($participant));
		$data['action'] = 'participant/edit_submit/' . $participant_id;
		$data = add_fields($data, 'participant', $participant);

		// dob is a bit of a nasty one, as is the languages component...
		$data['dob'] = output_date($participant->dateofbirth, TRUE);
		$data['comment'] = $this->input->post('comment');;
		$languages = empty($language_array)
		? $this->languageModel->get_languages_by_participant($participant_id)
		: $this->create_language_objects($language_array);
		if (empty($languages)) $languages = $this->create_language_objects($language_array);
		$data['languages'] = $languages;
		$data['is_registration'] = FALSE;

		// Load the view
		if ($header) $this->load->view('templates/header', $data);
		else $this->load->view('templates/simple_header', $data);
		$this->load->view('participant_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the edit of a participant */
	public function edit_submit($participant_id)
	{
		// Run validation
		if (!$this->validate_participant(FALSE))
		{
			// If not succeeded, show form again with error messages
			$language_array = $this->create_language_array();
			$this->edit($participant_id, $language_array);
		}
		else
		{
			// If succeeded, update the pariticipant
			$participant = $this->post_participant();
			$this->participantModel->update_participant($participant_id, $participant);

			// Add the (possible) comment
			$comment = $this->post_comment($participant_id);
			if ($comment) $this->commentModel->add_comment($comment);

			// Create/update the languages
			$this->create_languages($participant_id);

			// Display success
			$p = $this->participantModel->get_participant_by_id($participant_id);
			flashdata(sprintf(lang('participant_edited'), name($p)));
			redirect('/participant/', 'refresh');
		}
	}

	/////////////////////////
	// Registration
	/////////////////////////

	/** Specifies the contents of the register page */
	public function register($language = L::ENGLISH, $language_array = [])
	{
		reset_language($language);

		$participant_id = $this->session->userdata('participant_id');
		$participant = !is_null($participant_id) ? $this->participantModel->get_participant_by_id($participant_id) : NULL;

		$data['page_title'] = lang('reg_pp');
		$data['action'] = $language === L::ENGLISH ? '/signup_submit/' : '/aanmelden_versturen/';
		$data['current_language'] = $language;
		$data = add_fields($data, 'participant', $participant);

		// Empty fields related to participant if $participant_id is set
		if ($participant_id)
		{
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['gender'] = '';
			$data['dob'] = '';
			$data['birthweight'] = '';
			$data['pregnancyweeks'] = '';
			$data['pregnancydays'] = '';
        }

		// Set standard fields
		$data['dob'] = '';
		$data['comment'] = $this->input->post('comment');
		$data['is_registration'] = TRUE;
        $data['languages'] = $this->create_language_objects($language_array);

        $this->load->view('templates/register_header', $data);
		$this->load->view('participant_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the registration of a participant */
	public function register_submit($language = L::ENGLISH)
	{
		// Reset the language
		reset_language($language);

		// Run validation
		if (!$this->validate_participant(TRUE))
		{
			// If not succeeded, show form again with error messages
            $language_array = $this->create_language_array();
            $this->register($language, $language_array);
		}
		else
		{
			// If succeeded, create the pariticipant
			$participant = $this->post_participant();
			$participant_id = $this->participantModel->add_participant($participant);

			// Add the (possible) comment
			$comment = $this->post_comment($participant_id);
			if ($comment) $this->commentModel->add_comment($comment);

            // Create the languages
            $this->create_languages($participant_id);

			// Don't activate on registration (let admins decide)
			$this->participantModel->deactivate($participant_id, DeactivateReason::NEW_PARTICIPANT);
			$p = $this->participantModel->get_participant_by_id($participant_id);

			// Send confirmation e-mail to participant
			$this->email->clear();
			$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
			$this->email->to(in_development() ? TO_EMAIL_DEV_MODE : $p->email);
			$this->email->subject(lang('reg_subject'));

			$message = '<p>' . sprintf(lang('mail_heading'), parent_name($p)) . '</p>';
			$message .= sprintf(lang('reg_thanks'), name($p));
			$message .= sprintf(lang('reg_body'), gender_child($p->gender), $p->firstname);
			$message .= lang('reg_ending');
			$message .= lang('mail_disclaimer');

			$this->email->message($message);
			$this->email->send();

			// Send an e-mail to all admins to activate
			$url = $this->config->site_url() . 'participant/get/' . $participant_id;
			$users = $this->userModel->get_all_admins();
			foreach ($users as $user)
			{
				reset_language(user_language($user));

				$this->email->clear();
				$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
				$this->email->to(in_development() ? TO_EMAIL_DEV_MODE : $user->email);
				$this->email->subject(lang('reg_pp_subject'));

				$message = sprintf(lang('mail_heading'), $user->username);
				$message .= '<br /><br />';
				$message .= sprintf(lang('reg_pp_body'), $url, $url);
				$message .= '<br /><br />';
				$message .= lang('mail_ending');
				$message .= '<br /><br />';
				$message .= lang('mail_disclaimer');

				$this->email->message($message);
				$this->email->send();
			}

			// Display success
			$url = $language === L::ENGLISH ? '/signup_finished/' : '/aanmelden_afgerond/';
			redirect($url, 'refresh');
		}
	}

	/** Specifies the contents of the finish registration page */
	public function register_finish($language = L::ENGLISH)
	{
		reset_language($language);
		$data['current_language'] = $language;
		$data['page_title'] = lang('register_finish');
		$data['page_info'] = lang('register_info');

		$this->load->view('templates/register_header', $data);
		$this->load->view('register_finish_view', $data);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// De-registration
	/////////////////////////

	/** Specifies the contents of the register page */
	public function deregister($language = L::ENGLISH)
	{
		reset_language($language);

		$data['page_title'] = lang('dereg_pp');
		$data['action'] = $language === L::ENGLISH ? '/deregister_submit/' : '/afmelden_versturen/';
		$data['current_language'] = $language;
		$data = add_fields($data, 'participant');

		// dob is a bit of a nasty one
		$data['dob'] = '';

		$this->load->view('templates/register_header', $data);
		$this->load->view('participant_deregister', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the registration of a participant */
	public function deregister_submit($language)
	{
		// Reset the language
		reset_language($language);

		// Run validation
		if (!$this->validate_deregister())
		{
			// If not succeeded, show form again with error messages
			$this->deregister($language);
		}
		else
		{
			// If succeeded, send e-mail
			$name = $this->input->post('firstname') . ' ' . $this->input->post('lastname');
			$dob = $this->input->post('dob');
			$email = $this->input->post('email');
			$reason = $this->input->post('reason');

			$users = $this->userModel->get_all_admins();
			foreach ($users as $user)
			{
				reset_language(user_language($user));

				$this->email->clear();
				$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
				$this->email->to(in_development() ? TO_EMAIL_DEV_MODE : $user->email);
				$this->email->subject(lang('dereg_pp_subject'));

				$message = sprintf(lang('mail_heading'), $user->username);
				$message .= '<br /><br />';
				$message .= sprintf(lang('dereg_pp_body'), $name, $dob, $email, $reason);
				$message .= '<br /><br />';
				$message .= lang('mail_ending');
				$message .= '<br /><br />';
				$message .= lang('mail_disclaimer');

				$this->email->message($message);
				$this->email->send();
			}

			// Finish registration
			$url = $language === L::ENGLISH ? '/deregister_finished/' : '/afmelden_afgerond/';
			redirect($url, 'refresh');
		}
	}

	/** Specifies the contents of the finish registration page */
	public function deregister_finish($language = L::ENGLISH)
	{
		reset_language($language);
		$data['current_language'] = $language;
		$data['page_title'] = lang('deregister_finish');
		$data['page_info'] = lang('deregister_info');

		$this->load->view('templates/register_header', $data);
		$this->load->view('register_finish_view', $data);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// Other views
	/////////////////////////

	/** Shows all newly registered participants */
	public function registered()
	{
		create_participant_table();
		$data['ajax_source'] = 'participant/table_registered/';
		$data['page_title'] = lang('participants');
		$data['hide_columns'] = '9';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Finds available participants for an experiment
	 * @param integer $experiment_id
	 * @param integer $weeks_ahead
	 */
	public function find($experiment_id, $weeks_ahead = WEEKS_AHEAD)
	{
		$experiment = $this->experimentModel->get_experiment_by_id($experiment_id);

		// Create the table
		create_participant_table(NULL, TRUE);
		$data['ajax_source'] = 'participant/table_by_experiment/' . $experiment_id . '/' . $weeks_ahead;
		$data['sort_column'] = 1; // Sort on date of birth
		$data['sort_order'] = 'desc';  // Youngest first
		$data['page_title'] = sprintf(lang('callable_for'), $experiment->name);

		// Display some information on current participants.
		$info = sprintf(lang('call_info'), $experiment->name, $weeks_ahead);
		$info .= '<p>' . lang('call_experiment_info') . '</p>';
		if (is_risk($experiment))
		{
			$risks = $this->participationModel->get_participations_by_experiment($experiment_id, TRUE);
			$controls = $this->participationModel->get_participations_by_experiment($experiment_id, FALSE);
			$risk = lcfirst($experiment->dyslexic ? lang('dyslexic') : lang('multilingual'));
			$participations = array(
					sprintf(lang('call_n_of_risc'), count($risks), $risk),
					sprintf(lang('call_n_of_controll'), count($controls))
				);
			$info .= ul($participations);
		}
		else
		{
			$participations = $this->participationModel->get_participations_by_experiment($experiment_id);
			$info .= ul(array(count($participations) . '&nbsp;' . lcfirst(lang('participants')) . '.'));
		}
		$data['page_info'] = $info;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Shows a graph of participants in the database
	 */
	public function graph()
	{
		$data['page_title'] = 'Proefpersonen per jaar/maand';

		$this->load->view('templates/header', $data);
		$this->load->view('participant_graph', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Returns the JSON for the participant graph
	 */
	public function graph_json()
	{
		$table = array();
		$table['cols'] = array(
			array('label' => lang('graph_show_by'), 'type' => 'string'),
			array('label' => lang('year'), 'type' => 'string'),
			array('label' => lang('month'), 'type' => 'string'),
			array('label' => lang('control'), 'type' => 'number'),
			array('label' => lang('dyslexic'), 'type' => 'number'),
			array('label' => lang('multilingual'), 'type' => 'number'),
			array('label' => lang('both'), 'type' => 'number'),
		);

		$dob_count = array();
		$created_count = array();

		$participants = $this->participantModel->get_all_participants(TRUE);
		foreach ($participants AS $participant)
		{
			$dob_month = date('Y-m', strtotime($participant->dateofbirth));
			$created_month = date('Y-m', strtotime($participant->created));
			$d = $participant->dyslexicparent != NULL;
			$m = $participant->multilingual;
			// Converting to integer to allow easier fetching
			$type = 3 * $d + 5 * $m;

			$this->add_to_tally($dob_count, $dob_month, $type);
			$this->add_to_tally($created_count, $created_month, $type);
		}
		ksort($dob_count);
		ksort($created_count);

		$dob_rows = $this->create_rows($dob_count, lang('dob'));
		$created_rows = $this->create_rows($created_count, lang('registered'));
		$table['rows'] = $this->flatten(array_merge($dob_rows, $created_rows));
		echo json_encode($table);
	}

	/**
	 * Adds one or creates a new tally in the $count array.
	 */
	private function add_to_tally(&$count, $month, $type)
	{
		if (!isset($count[$month][$type]))
		{
			$count[$month][$type] = 1;
		}
		else
		{
			$count[$month][$type]++;
		}
	}

	/**
	 * Creates a rows array in the Google Charts format.
	 */
	private function create_rows($count, $type)
	{
		$nr = 0;
		$rows = array();
		foreach ($count as $k => $v)
		{
			$rows[$nr][0] = array('v' => $type);
			$rows[$nr][1] = array('v' => substr($k, 0, 4));
			$rows[$nr][2] = array('v' => strftime('%h %Y', strtotime($k)));
			if (isset($v[0])) $rows[$nr][3] = array('v' => $v[0]);
			if (isset($v[3])) $rows[$nr][4] = array('v' => $v[3]);
			if (isset($v[5])) $rows[$nr][5] = array('v' => $v[5]);
			if (isset($v[8])) $rows[$nr][6] = array('v' => $v[8]);

			$nr++;
		}

		return $rows;
	}

	/**
	 * Flattens the rows to the Google Charts format
	 */
	private function flatten($rows)
	{
		$result = array();
		foreach ($rows as $row)
		{
			array_push($result, array('c' => array_values($row)));
		}
		return $result;
	}

	/**
	 * Shows an age overview of all participants
	 */
	public function age_overview()
	{
		// Get the number of months to look in the future
		$date = is_null($this->input->post('date')) ? output_date('now', TRUE) : $this->input->post('date');

		// Set up the table
		base_table();
		$this->table->set_heading('Leeftijd in maanden', 'Aantal actieve proefpersonen', 'Aantal dyslectisch', 'Aantal meertalig', lang('actions'));

		// Calculate the number of participants per month, given the data
		foreach ($this->participantModel->get_participants_per_month($date) as $p)
		{
			$this->table->add_row($p->age, $p->count, $p->dyslexic, $p->multilingual,
				anchor('participant/age_overview_detail/' . $p->age . '/' . $date, img_zoom('participants')));
		}

		$data['table'] = $this->table->generate();
		$data['date'] = $date;
		$data['page_info'] = $this->load->view('participant_age_overview', $data, TRUE);
		$data['page_title'] = lang('participants');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/table_view', $data);
		$this->load->view('templates/footer');
	}


	/**
	 * Shows an age overview of all participants
	 */
	public function age_overview_detail($age_in_months, $date)
	{
		create_participant_table();
		$data['ajax_source'] = 'participant/table_by_age/' . $date . '/' . $age_in_months;
		$data['page_title'] = lang('participants');
		$data['hide_columns'] = '8';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// Other actions
	/////////////////////////

	/** Activates the specified participant */
	public function activate($participant_id)
	{
		$this->participantModel->activate($participant_id);
		$participant = $this->participantModel->get_participant_by_id($participant_id);
		flashdata(sprintf(lang('p_activated'), name($participant)));
		redirect($this->agent->referrer(), 'refresh');
	}

	/** Deactivates the specified participant */
	public function deactivate($participant_id)
	{
		$this->participantModel->deactivate($participant_id, DeactivateReason::MANUAL);
		$p = $this->participantModel->get_participant_by_id($participant_id);

		// Inform all admins of this deactivation
		$url = $this->config->site_url() . 'participant/get/' . $participant_id;
		$users = $this->userModel->get_all_admins();
		foreach ($users as $user)
		{
			reset_language(user_language($user));

			$this->email->clear();
			$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
			$this->email->to(in_development() ? TO_EMAIL_DEV_MODE : $user->email);
			$this->email->subject(lang('dereg_pp_subject'));

			$message = sprintf(lang('mail_heading'), $user->username);
			$message .= '<br /><br />';
			$message .= sprintf(lang('deac_pp_body'), name($p), $p->phone, current_username(), $url, $url);
			$message .= '<br /><br />';
			$message .= lang('mail_ending');
			$message .= '<br /><br />';
			$message .= lang('mail_disclaimer');

			$this->email->message($message);
			$this->email->send();
		}

		flashdata(sprintf(lang('p_deactivated'), name($p)));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates a participant */
	private function validate_participant($is_registration)
	{
		$this->form_validation->set_rules('firstname', lang('firstname'), 'trim|required');
		$this->form_validation->set_rules('lastname', lang('lastname'), 'trim|required');
		$this->form_validation->set_rules('gender', lang('gender'), 'trim|required');
		$this->form_validation->set_rules('dob', lang('dob'), 'trim|required|callback_in_future');
		$this->form_validation->set_rules('parentfirstname', lang('parentfirstname'), 'trim|required');
		$this->form_validation->set_rules('parentlastname', lang('parentlastname'), 'trim');
		$this->form_validation->set_rules('city', lang('city'), 'trim');
		$this->form_validation->set_rules('phone', lang('phone'), 'trim|required');
		$this->form_validation->set_rules('phonealt', lang('phonealt'), 'trim');
		$this->form_validation->set_rules('email', lang('email'), 'trim|valid_email');
		$this->form_validation->set_rules('dyslexicparent', lang('dyslexicparent'), 'required');
		$this->form_validation->set_rules('languagedisorderparent', lang('languagedisorderparent'), 'required');
        $this->form_validation->set_rules('speechdisorderparent', lang('speechdisorderparent'), 'required');
		$this->form_validation->set_rules('multilingual', lang('multilingual'), 'required');
		$this->form_validation->set_rules('percentage', lang('percentage'), 'callback_sum_percentage');
		$this->form_validation->set_rules('origin', lang('origin'), 'callback_not_empty');

		if ($is_registration)
		{
			$this->form_validation->set_rules('birthweight', lang('birthweight'), 'trim|required|greater_than[500]|less_than[6000]');
			$this->form_validation->set_rules('pregnancyweeks', lang('pregnancyweeks'), 'trim|required|greater_than[20]|less_than[50]');
			$this->form_validation->set_rules('pregnancydays', lang('pregnancydays'), 'trim|required|less_than[7]');
		}

		return $this->form_validation->run();
	}

	/** Validates deregistration of a participant */
	private function validate_deregister()
	{
		$this->form_validation->set_rules('firstname', lang('firstname'), 'trim|required');
		$this->form_validation->set_rules('lastname', lang('lastname'), 'trim|required');
		$this->form_validation->set_rules('dob', lang('dob'), 'trim|required');
		$this->form_validation->set_rules('email', lang('email'), 'trim|valid_email');
		$this->form_validation->set_rules('reason', lang('reason'), 'trim|required');

		return $this->form_validation->run();
	}

	/** Posts the data for a participant */
	private function post_participant()
	{
		$dyslexicparent = $this->input->post('dyslexicparent');
		$languagedisorderparent = $this->input->post('languagedisorderparent');
		$speechdisorderparent = $this->input->post('speechdisorderparent');

		return array(
                'firstname' 			       => htmlentities($this->input->post('firstname')),
				'lastname' 				       => htmlentities($this->input->post('lastname')),
				'gender' 				       => $this->input->post('gender'),
				'dateofbirth'			       => input_date($this->input->post('dob')),
				'birthweight' 			       => $this->input->post('birthweight'),
				'pregnancyweeks' 		       => $this->input->post('pregnancyweeks'),
				'pregnancydays' 		       => $this->input->post('pregnancydays'),
				'dyslexicparent' 		       => $dyslexicparent === Gender::NONE ? NULL : $dyslexicparent,
				'languagedisorderparent'       => $languagedisorderparent === Gender::NONE ? NULL : $languagedisorderparent,
				'speechdisorderparent'         => $speechdisorderparent === Gender::NONE ? NULL : $speechdisorderparent,
				'speechdisorderparent_details' => $this->input->post('speechdisorderparent_details'),
				'multilingual' 			       => $this->input->post('multilingual'),
				'parentfirstname' 		       => htmlentities($this->input->post('parentfirstname')),
				'parentlastname' 		       => htmlentities($this->input->post('parentlastname')),
				'city' 					       => htmlentities($this->input->post('city')),
				'phone' 				       => htmlentities($this->input->post('phone')),
				'phonealt' 				       => htmlentities($this->input->post('phonealt')),
				'email'					       => filter_var($this->input->post('email'), FILTER_SANITIZE_EMAIL),
				'origin'				       => '',
                'newsletter'                   => $this->input->post('newsletter'),
                'english_communication'        => $this->input->post('english_communication'),
		);
	}

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
	// Dealing with languages
	/////////////////////////

	private function create_language_array()
	{
		$languages = $this->input->post('language');
		$percentages = $this->input->post('percentage');

		$language_array = array();
		for ($i = 0; $i < count($languages); $i++)
		{
			if ($languages[$i])
			{
			    if(!$percentages[$i])
			         $percentages[$i] = null;

				$l = array(
					'language'			=> $languages[$i],
					'percentage'		=> $percentages[$i]
				);
				array_push($language_array, $l);
			}
		}

		return $language_array;
	}

	private function create_languages($participant_id)
	{
		$this->languageModel->delete_languages_by_participant($participant_id);

		$language_array = $this->create_language_array();
		if (empty($language_array)) return;

		foreach ($language_array as $l)
		{
			$l['participant_id'] = $participant_id;

			if(!array_key_exists('percentage', $l) || $l['percentage'] == null)
			    $l['percentage'] = 0;

			$this->languageModel->add_language($l);
		}
	}

	private function create_language_objects($language_array)
	{
		$languages = array((object) array('language' => '', 'percentage' => ''));

		if ($language_array)
		{
			$languages = array();
			foreach ($language_array as $l)
			{
				array_push($languages, (object) $l);
			}
		}

		return $languages;
	}

	/////////////////////////
	// Callbacks
	/////////////////////////

	/** Checks whether the given date is in the past */
	public function in_future($value)
	{
		if (strtotime($value) > time())
		{
			$this->form_validation->set_message('in_future', lang('date_in_future'));
			return FALSE;
		}
		return TRUE;
	}

	/** Checks whether the given parameter is higher than 0 */
	public function not_empty($value)
	{
		if ($value === '0')
		{
			$this->form_validation->set_message('not_empty', lang('form_validation_isset'));
			return FALSE;
		}
		return TRUE;
	}

	/** Checks whether the sum of percentages adds up to 100 */
	public function sum_percentage($percentage)
	{
		$multilingual = $this->input->post('multilingual');
		if (empty($multilingual)) return TRUE;

		$percentage = $this->input->post('percentage');

		if ($percentage && array_sum($percentage) != 100)
		{
			$this->form_validation->set_message('sum_percentage', lang('sum_percentage_wrong'));
			return FALSE;
		}

        return TRUE;
	}

	/////////////////////////
	// AJAX
	/////////////////////////

	/** Filters the participants on the given term */
	public function filter_participants()
	{
		$term = $this->input->get('term');
		if(is_null($term))
		    return json_encode([]);

		$participants = $this->participantModel->find_participants_by_name($term);

		echo json_encode($participants);
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$participant_ids = array();
		if (current_role() == UserRole::CALLER)
		{
			$experiments = $this->callerModel->get_experiments_by_caller(current_user_id());
			foreach ($experiments as $experiment)
			{
				$find_p = $this->participantModel->find_participants($experiment);
				$part_p = $this->experimentModel->get_participants_by_experiment($experiment->id);

				$participant_ids = array_merge($participant_ids, get_object_ids($find_p));
				$participant_ids = array_merge($participant_ids, get_object_ids($part_p));
			}
		}
		if (current_role() == UserRole::LEADER)
		{
			$experiments = $this->leaderModel->get_experiments_by_leader(current_user_id());
			foreach ($experiments as $experiment)
			{
				$part_p = $this->experimentModel->get_participants_by_experiment($experiment->id);

				$participant_ids = array_merge($participant_ids, get_object_ids($part_p));
			}
		}

		$this->datatables->select('CONCAT(firstname, " ", lastname) AS p, dateofbirth, dateofbirth as age, dyslexicparent, multilingual, languagedisorderparent, phone, english_communication, id, CONCAT(parentfirstname, " ", parentlastname)', FALSE);
		$this->datatables->from('participant');

		if (!is_admin())
		{
			if (empty($participant_ids)) $this->datatables->where('id', 0)	; // no participants then
			else $this->datatables->where('id IN (' . implode(',', $participant_ids) . ')');
		}

		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(id)');
		$this->datatables->edit_column('dateofbirth', '$1', 'dob(dateofbirth)');
		$this->datatables->edit_column('age', '$1', 'age_in_months_and_days(age)');
		$this->datatables->edit_column('dyslexicparent', '$1', 'img_tick(dyslexicparent)');
		$this->datatables->edit_column('multilingual', '$1', 'img_tick(multilingual)');
		$this->datatables->edit_column('languagedisorderparent', '$1', 'img_tick(languagedisorderparent)');
		$this->datatables->edit_column('english_communication', '$1', 'img_tick_null(english_communication)');
		$this->datatables->edit_column('id', '$1', 'participant_actions(id)');

		echo $this->datatables->generate();
	}

	public function table_registered()
	{
		$this->datatables->where('deactivated IS NOT NULL');
		$this->datatables->where('deactivated_reason', DeactivateReason::NEW_PARTICIPANT);
		$this->table();
	}

	public function table_by_age($date, $age_in_months)
	{
		$this->datatables->where('TIMESTAMPDIFF(MONTH, dateofbirth, "' . input_date($date) . '") = ' . $age_in_months);
		$this->datatables->where('activated', TRUE);
		$this->table();
	}

	public function table_by_testsurvey($testsurvey_id)
	{
		$testsurvey = $this->testSurveyModel->get_testsurvey_by_id($testsurvey_id);
		$participants = $this->participantModel->find_participants_by_testsurvey($testsurvey);
		$participant_ids = get_object_ids($participants);

		$this->datatables->select('CONCAT(firstname, " ", lastname) AS p, dateofbirth, dateofbirth as age, dyslexicparent, multilingual, languagedisorderparent, phone, english_communication, id', FALSE);
		$this->datatables->from('participant');

		if (empty($participant_ids)) $this->datatables->where('id', 0)	; // no participants then
		else $this->datatables->where('id IN (' . implode(',', $participant_ids) . ')');

		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(id)');
		$this->datatables->edit_column('dateofbirth', '$1', 'dob(dateofbirth)');
		$this->datatables->edit_column('age', '$1', 'age_in_months_and_days(age)');
		$this->datatables->edit_column('dyslexicparent', '$1', 'img_tick(dyslexicparent)');
		$this->datatables->edit_column('multilingual', '$1', 'img_tick(multilingual)');
		$this->datatables->edit_column('languagedisorderparent', '$1', 'tos(languagedisorderparent)');
        $this->datatables->edit_column('english_communication', '$1', 'img_tick_null(english_communication)');
        $this->datatables->edit_column('id', '$1', 'testsurvey_participant_actions(' . $testsurvey_id . ', id)');

		echo $this->datatables->generate();
	}

	public function table_by_experiment($experiment_id = NULL, $weeks_ahead = WEEKS_AHEAD)
	{
		$experiment = $this->experimentModel->get_experiment_by_id($experiment_id);
		$participants = $this->participantModel->find_participants($experiment, $weeks_ahead);
		$participant_ids = get_object_ids($participants);

		$this->datatables->select('CONCAT(firstname, " ", lastname) AS p, dateofbirth, dateofbirth as age, dyslexicparent, multilingual, languagedisorderparent, phone, english_communication, lastcalled, participant.id AS id', FALSE);
		$this->datatables->from('participant');
		// Don't split this in two lines, see https://github.com/EllisLab/CodeIgniter/pull/759
		$this->datatables->join('participation', 'participation.participant_id = participant.id AND participation.experiment_id = ' . $experiment_id, 'left');

		if (empty($participant_ids)) $this->datatables->where('participant.id', 0)	; // no participants then
		else $this->datatables->where('participant.id IN (' . implode(',', $participant_ids) . ')');

		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(id)');
		$this->datatables->edit_column('dateofbirth', '$1', 'dob(dateofbirth)');
		$this->datatables->edit_column('age', '$1', 'age_in_months_and_days(age)');
		$this->datatables->edit_column('dyslexicparent', '$1', 'img_tick(dyslexicparent)');
		$this->datatables->edit_column('multilingual', '$1', 'img_tick(multilingual)');
		$this->datatables->edit_column('languagedisorderparent', '$1', 'tos(languagedisorderparent)');
        $this->datatables->edit_column('english_communication', '$1', 'img_tick_null(english_communication)');
        $this->datatables->edit_column('lastcalled', '$1', 'last_called(id, ' . $experiment_id . ')');
        $this->datatables->edit_column('id', '$1', 'participant_call_actions(id, ' . $experiment_id . ', ' . $weeks_ahead . ')');

		echo $this->datatables->generate();
	}
}
