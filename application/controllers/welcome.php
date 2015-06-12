<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());

		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}

	/////////////////////////
	// Caller/Leader redirect
	/////////////////////////

	/** Specifies the content for the admin interface view. */
	public function index()
	{
		switch (current_role())
		{
			case UserRole::Caller:
				$this->caller_interface(current_user_id());
				break;
			case UserRole::Leader:
				$this->leader_interface(current_user_id());
				break;
			case UserRole::Admin:
				$this->admin_interface();
				break;
			case UserRole::System:
				redirect('appointment');
				break;
			default:
				show_404();
				break;
		}
	}

	/////////////////////////
	// Admin interface view
	/////////////////////////

	/** Specifies the content for the admin interface view. */
	public function admin_interface()
	{
		$data['page_title'] = sprintf(lang('welcome'), current_username());
		$data['prio_comment_nr'] = count($this->commentModel->get_all_comments(TRUE));
		$data['call_min_exp'] = count($this->callerModel->get_experiments_without_callers());
		$data['leader_min_exp'] = count($this->leaderModel->get_experiments_without_leaders());

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('admin_interface', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// Caller interface view
	/////////////////////////

	/** Specifies the content for the caller interface view. */
	public function caller_interface($user_id)
	{
		if (!correct_user($user_id)) return;

		$user = $this->userModel->get_user_by_id($user_id);
		$experiments = $this->callerModel->get_experiments_by_caller($user_id);
		$nr_experiments = count($experiments);

		// Count total number of participants (and especially for longitudinal experiments)
		$longitudinal = array();
		$nr_participants = 0;
		foreach ($experiments as $e)
		{
			$n = count($this->participantModel->find_participants($e));
			$nr_participants += $n;

			$prereqs = $this->relationModel->get_relation_ids_by_experiment($e->id, RelationType::Prerequisite, TRUE);
			if ($prereqs && $n > 0)
			{
				$longitudinal[$e->name] = $n;
			}
		}

		// Count testinvites that need to be reminded manually
		$testinvite_count = $this->testInviteModel->count_to_be_reminded_testinvites();
		$testinvite_url = array('url' => 'testinvite/index/1', 'title' => sprintf(lang('testinvite_action'), $testinvite_count));

		create_experiment_table();
		$data['ajax_source'] = 'experiment/table/0/' . $user_id;
		$data['page_title'] = sprintf(lang('welcome'), $user->username);
		$data['page_info'] = 
			sprintf(lang('info_caller'), $nr_experiments, $nr_participants) . 
			$this->construct_longitudinal_message($longitudinal);
		$data['action_urls'] = array($testinvite_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Caller);
		$this->load->view('templates/footer');
	}

	/**
	 * Constructs a warning message for all longitudinal experiments
	 */
	private function construct_longitudinal_message($longitudinal)
	{
		$messages = array();
		if ($longitudinal)
		{
			foreach ($longitudinal as $l => $n)
			{
				array_push($messages, sprintf(lang('callable_longitudinal'), $n, $l));
			}
		}
		return $messages ? ul($messages, array('class' => 'warning')) : '';
	}

	/////////////////////////
	// Leader interface view
	/////////////////////////

	/** Specifies the content for the caller interface view. */
	public function leader_interface($user_id)
	{
		if (!correct_user($user_id)) return;

		$user = $this->userModel->get_user_by_id($user_id);
		$experiments = $this->leaderModel->get_experiments_by_leader($user_id);
		$nr_experiments = count($experiments);
		$conf_part = count($this->participationModel->get_confirmed_participations($experiments));
		$conf_url = array('url' => 'participation', 'title' => sprintf(lang('part_action'), $conf_part));

		create_experiment_table();
		$data['ajax_source'] = 'experiment/table/0/0/' . $user_id;
		$data['page_title'] = sprintf(lang('welcome'), $user->username);
		$data['page_info'] = sprintf(lang('info_leader'), $nr_experiments);
		$data['action_urls'] = array($conf_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Leader);
		$this->load->view('templates/footer');
	}
}
