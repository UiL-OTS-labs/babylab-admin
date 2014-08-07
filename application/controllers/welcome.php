<?php
class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
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
			case UserRole::System:
				show_404();
				break;
			default:
				$this->admin_interface();
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

		$nr_participants = 0;
		foreach ($experiments as $e)
		{
			$nr_participants += count($this->participantModel->find_participants($e));
		}
		$nr_experiments = count($experiments);

		create_experiment_table();
		$data['ajax_source'] = 'experiment/table/0/' . $user_id;
		$data['page_title'] = sprintf(lang('welcome'), $user->username);
		$data['page_info'] = sprintf(lang('info_caller'), $nr_experiments, $nr_participants);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Caller);
		$this->load->view('templates/footer');
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
