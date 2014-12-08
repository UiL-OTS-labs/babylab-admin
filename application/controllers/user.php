<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller
{
	// TODO: block ip's after multiple incorrect logins (failed_logins table)

	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except(array(
			'register', 'register_submit', 'register_finish',
			'forgot_password', 'forgot_password_submit', 
			'reset_password', 'reset_password_submit'));
		reset_language(current_language());

		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/**
	 *
	 * Specifies the contents of the default page.
	 */
	public function index()
	{
		$add_url = array('url' => 'user/add', 'title' => lang('add_user'));

		create_user_table();
		$data['ajax_source'] = 'user/table/';
		$data['page_title'] = lang('users');
		$data['action_urls'] = array($add_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/**
	 *
	 * Shows the page for a single user
	 * @param int $user_id
	 */
	public function get($user_id)
	{
		$user = $this->userModel->get_user_by_id($user_id);

		$data['user'] = $user;
		$data['page_title'] = sprintf(lang('data_for_user'), $user->username);

		$this->load->view('templates/header', $data);
		$this->load->view('user_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 *
	 * Specifies the contents of the add user page
	 */
	public function add()
	{
		if (!is_admin()) return;

		$data['page_title'] = lang('add_user');
		$data['action'] = 'user/add_submit';
		$data['new_user'] = TRUE;
		$data = add_fields($data, 'user');

		$this->load->view('templates/header', $data);
		$this->load->view('user_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 *
	 * Submits the addition of a user
	 */
	public function add_submit()
	{
		if (!is_admin()) return;

		// Validation rules
		$regex = '/^[a-zA-Z0-9_]{1,60}$/';
		$this->form_validation->set_rules('username', lang('username'), 'trim|required|max_length[20]|regex_match[ ' . $regex . ']|is_unique[user.username]');
		$this->form_validation->set_rules('password', lang('password'), 'required|min_length[8]|max_length[72]|matches[password_conf]');
		$this->form_validation->set_rules('password_conf', lang('password_conf'), 'required');
		$this->form_validation->set_rules('email', lang('email'), 'trim|required|valid_email');
		$this->validate_user();

		// Run validation
		if ($this->form_validation->run() == FALSE)
		{
			// If not succeeded, show form again with error messages
			$this->add();
		}
		else
		{
			// If succeeded, insert user into database
			$user = $this->post_user();
			$this->userModel->add_user($user);

			flashdata(lang('user_added'));
			redirect('/user/', 'refresh');
		}
	}

	/**
	 *
	 * Specifies the contents of the edit user page
	 * @param $user_id
	 */
	public function edit($user_id)
	{
		if (!is_admin() && !correct_user($user_id)) return;

		$user = $this->userModel->get_user_by_id($user_id);

		$data['page_title'] = sprintf(lang('edit_user'), $user->username);
		$data['action'] = 'user/edit_submit/' . $user_id;
		$data['new_user'] = FALSE;
		$data = add_fields($data, 'user', $user);

		$this->load->view('templates/header', $data);
		$this->load->view('user_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 *
	 * Submits the edit of a user
	 * @param $user_id
	 */
	public function edit_submit($user_id)
	{
		if (!is_admin() && !correct_user($user_id)) return;

		// Validation rules
		$this->form_validation->set_rules('email', lang('email'), 'trim|required|valid_email');
		$this->validate_user();

		// Run validation
		if ($this->form_validation->run() == FALSE)
		{
			// If not succeeded, show form again with error messages
			$this->edit($user_id);
		}
		else
		{
			// If succeeded, update user in database
			$user = $this->post_user(FALSE);
			$this->userModel->update_user($user_id, $user);

			// Update session when current user is edited?!
			if (current_user_id() == $user_id)
			{
				$u = $this->userModel->get_user_by_id($user_id);
				$this->session->set_userdata('language', user_language($u));
			}

			flashdata(lang('user_edited'));
			redirect('welcome', 'refresh');
		}
	}

	/**
	 *
	 * Deletes a user
	 * TODO: check whether user exists (404)
	 * @param $user_id
	 */
	public function delete($user_id)
	{
		$user = $this->userModel->get_user_by_id($user_id);
		$this->userModel->delete_user($user_id);
		flashdata(sprintf(lang('deleted_user'), $user->username));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Registration
	/////////////////////////

	/**
	 *
	 * Specifies the contents of the register page
	 * @param $language The language for this page
	 */
	public function register($language = L::English)
	{
		reset_language($language);

		$data['page_title'] = lang('reg_user');
		$data['language'] = $language;
		$data['action'] = 'user/register_submit/' . $language;
		$data['new_user'] = TRUE;
		$data = add_fields($data, 'user');

		$this->load->view('templates/header', $data);
		$this->load->view('user_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 *
	 * Submits the registration of a user
	 * @param $language The language for this page
	 */
	public function register_submit($language = L::English)
	{
		// Reset the language
		reset_language($language);

		// Validation rules
		$regex = '/^[a-zA-Z0-9_]{1,60}$/';
		$this->form_validation->set_rules('username', lang('username'), 'trim|required|max_length[20]|regex_match[ ' . $regex . ']|is_unique[user.username]');
		$this->form_validation->set_rules('password', lang('password'), 'required|min_length[8]|max_length[72]|matches[password_conf]');
		$this->form_validation->set_rules('password_conf', lang('password_conf'), 'required');
		$this->form_validation->set_rules('email', lang('email'), 'trim|required|valid_email');
		$this->validate_user();

		// Run validation
		if ($this->form_validation->run() == FALSE)
		{
			// If not succeeded, show form again with error messages
			$this->register($language);
		}
		else
		{
			// If succeeded, insert user into database (not activated)
			$user = $this->post_user();
			$user_id = $this->userModel->add_user($user);
			$this->userModel->set_activate($user_id, FALSE);
				
			// E-mail for activation to all admins
			$u = $this->userModel->get_user_by_id($user_id);
			$admins = $this->userModel->get_all_admins();
			foreach ($admins as $admin)
			{
				reset_language(user_language($admin));
					
				$this->email->clear();
				$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
				$this->email->to(EMAIL_DEV_MODE ? TO_EMAIL_OVERRIDE : $admin->email);
				$this->email->subject(lang('reg_user_subject'));
					
				$message = sprintf(lang('mail_heading'), $admin->username);
				$message .= br(2);
				$message .= sprintf(lang('reg_user_body'), $u->username, $u->email);
				$message .= br(2);
				$message .= lang('mail_ending');
				$message .= br(2);
				$message .= lang('mail_disclaimer');
					
				$this->email->message($message);
				$this->email->send();
			}

			// Finish registration
			redirect('/user/register_finish/' . $language, 'refresh');
		}
	}

	/**
	 *
	 * Specifies the contents of the finish registration page
	 * @param $language The language for this page
	 */
	public function register_finish($language = L::English)
	{
		reset_language($language);

		$data['page_title'] = lang('register');

		$this->load->view('templates/header', $data);
		$this->load->view('register_finish_view', $data);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// Other actions
	/////////////////////////

	/**
	 *
	 * Activates the specified user
	 * @param $user_id
	 */
	public function activate($user_id)
	{
		$this->userModel->set_activate($user_id, TRUE);
		$user = $this->userModel->get_user_by_id($user_id);
		flashdata(sprintf(lang('u_activated'), $user->username));
		redirect('/user/', 'refresh');
	}

	/**
	 *
	 * Deactivates the specified user
	 * @param $user_id
	 */
	public function deactivate($user_id)
	{
		if (current_user_id() == $user_id)
		{
			flashdata(lang('u_deactivated_self'), FALSE);
		}
		else
		{
			$this->userModel->set_activate($user_id, FALSE);
			$user = $this->userModel->get_user_by_id($user_id);
			flashdata(sprintf(lang('u_deactivated'), $user->username));
		}
		redirect('/user/', 'refresh');
	}

	/**
	 *
	 * Specifies the contents of the change password page
	 * @param $user_id
	 */
	public function change_password($user_id)
	{
		if (!correct_user($user_id)) return;

		$user = $this->userModel->get_user_by_id($user_id);

		$data['user_id'] 	= $user->id;
		$data['username']	= $user->username;
		$data['page_title'] = lang('change_password');

		$this->load->view('templates/header', $data);
		$this->load->view('user_change_password_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 *
	 * Submits the password change of a user
	 * @param $user_id
	 */
	public function change_password_submit($user_id)
	{
		if (!correct_user($user_id)) return;

		// Validation rules
		$this->form_validation->set_rules('password_prev', lang('password_prev'), 'required|callback_matches_password[' . $user_id . ']');
		$this->form_validation->set_rules('password_new', lang('password_new'), 'required|min_length[8]|max_length[72]|matches[password_conf]');
		$this->form_validation->set_rules('password_conf', lang('password_conf'), 'required');

		// Run validation
		if ($this->form_validation->run() == FALSE)
		{
			// If not succeeded, show form again with error messages
			$this->change_password($user_id);
		}
		else
		{
			// If succeeded, insert data into database (use user model)
			$user = array(
					'password' 	=> $this->phpass->hash($this->input->post('password_new'))
			);
			$this->userModel->update_user($user_id, $user);

			flashdata(lang('password_updated'));
			redirect('user', 'refresh');
		}
	}

	/**
	 *
	 * Specifies the contents of the forgot password page
	 * @param $language
	 */
	public function forgot_password($language = L::English)
	{
		reset_language($language);

		$data['page_title'] = lang('forgot_password');
		$data['language'] = $language;
		$data['action'] = 'user/forgot_password_submit/' . $language;

		$this->load->view('templates/header', $data);
		$this->load->view('user_forgot_password_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 *
	 * Submits the password reset request
	 * @param $language
	 */
	public function forgot_password_submit($language = L::English)
	{
		reset_language($language);

		// Validation rules
		$this->form_validation->set_rules('email', lang('email'), 'trim|required|valid_email|callback_email_exists|callback_reset_request_sent');

		// Run validation
		if ($this->form_validation->run() == FALSE)
		{
			// If not succeeded, show form again with error messages
			$this->forgot_password();
		}
		else
		{
			// If succeeded, lookup user by e-mail address and send password reset e-mail.
			$user = $this->userModel->get_user_by_email($this->input->post('email'));
			$url = bin2hex(openssl_random_pseudo_bytes(8));

			$reset_request = array(
					'activated'				=> NULL,
					'resetrequeststring'	=> $url,
					'resetrequesttime'		=> input_datetime()
			);

			$this->userModel->update_user($user->id, $reset_request);

			// Send out reset e-mail
			reset_language(user_language($user));
				
			$this->email->clear();
			$this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
			$this->email->to(EMAIL_DEV_MODE ? TO_EMAIL_OVERRIDE : $user->email);
			$this->email->subject(lang('resetpw_subject'));
				
			$message = sprintf(lang('mail_heading'), $user->username);
			$message .= br(2);
			$message .= sprintf(lang('resetpw_body'), anchor(base_url() . 'resetpw/' . $url));
			$message .= br(2);
			$message .= lang('mail_ending');
			$message .= br(2);
			$message .= lang('mail_disclaimer');
				
			$this->email->message($message);
			$this->email->send();

			// Show success
			flashdata(sprintf(lang('forgot_pw_sent'), $user->email));
			redirect('login', 'refresh');
		}
	}

	/**
	 *
	 * Specifies the contents of the reset password page
	 * @param $resetstring
	 */
	public function reset_password($resetstring = '')
	{
		$user = $this->userModel->get_user_by_resetstring($resetstring);

		if (empty($user) || $user->resetrequesttime < input_datetime('-1 day'))
		{
			$data['error'] = 'Incorrect URL or request timed out. Please send a new password reset request.';
			$this->load->view('templates/error', $data);
			return;
		}

		$data['resetstring']	= $resetstring;
		$data['page_title'] 	= 'Reset password';

		$this->load->view('templates/header', $data);
		$this->load->view('user_reset_password_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 *
	 * Submits the password reset
	 * @param $resetstring
	 */
	public function reset_password_submit($resetstring)
	{
		// Validation rules
		$this->form_validation->set_rules('password_new', lang('password_new'), 'required|min_length[8]|max_length[72]|matches[password_conf]');
		$this->form_validation->set_rules('password_conf', lang('password_conf'), 'required');

		// Run validation
		if ($this->form_validation->run() == FALSE) {
			// If not succeeded, show form again with error messages
			$this->reset_password($resetstring);
		}
		else {
			$u = $this->userModel->get_user_by_resetstring($resetstring);

			// If succeeded, update password, activate user, and nullify reset request
			$user = array(
					'password' 				=> $this->phpass->hash($this->input->post('password_new')),
					'activated'				=> input_datetime(),
					'resetrequeststring'	=> NULL,
					'resetrequesttime'		=> NULL
			);
			$this->userModel->update_user($u->id, $user);

			flashdata(lang('password_updated'));
			redirect('login', 'refresh');
		}
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/**
	 *
	 * Validates a user
	 */
	private function validate_user()
	{
		$this->form_validation->set_rules('role', lang('role'), 'trim|required');
		$this->form_validation->set_rules('phone', lang('phone'), 'trim');
		$this->form_validation->set_rules('mobile', lang('mobile'), 'trim');
		$this->form_validation->set_rules('preferredlanguage', lang('preferredlanguage'), 'trim|required');
	}
	
	/**
	 * Posts a user (when adding, updating, registering)
	 * @param $creating whether we're creating this user, if so, add username and password
	 */
	private function post_user($creating = TRUE)
	{
		$user = array(
				'role' 				=> $this->input->post('role'),
				'email'				=> $this->input->post('email'),
				'phone' 			=> $this->input->post('phone'),
				'mobile' 			=> $this->input->post('mobile'),
				'preferredlanguage' => $this->input->post('preferredlanguage')
		);
		
		if ($creating) 
		{
			$user['username'] = $this->input->post('username');
			$user['password'] = $this->phpass->hash($this->input->post('password'));
		}
		
		return $user;
	}

	/////////////////////////
	// Callbacks
	/////////////////////////

	/**
	 *
	 * Checks whether the entered password matches the previous one
	 * @param $password
	 * @param $user_id
	 */
	public function matches_password($password, $user_id)
	{
		$user = $this->userModel->get_user_by_id($user_id);
		if (!$this->phpass->check($password, $user->password))
		{
			$this->form_validation->set_message('matches_password', lang('prev_pass_incorrect'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 *
	 * Checks whether the entered e-mail exists in the database
	 * @param $email
	 */
	public function email_exists($email)
	{
		$user = $this->userModel->get_user_by_email($email);
		if ($user == NULL)
		{
			$this->form_validation->set_message('email_exists', lang('unknown_email'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 *
	 * Checks whether the entered e-mail is unique (apart from for the specified user).
	 * @param $email the e-mail address
	 * @param $user_id the user id (can be left empty)
	 * @deprecated No longer in use.
	 */
	public function unique_email($email, $user_id = NULL)
	{
		$user = $this->userModel->get_user_by_email($email);
		if ($user != NULL && $user->id != $user_id)
		{
			$this->form_validation->set_message('unique_email', lang('is_unique'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 *
	 * Checks whether there has already been sent a reset request
	 * @param $email
	 */
	public function reset_request_sent($email)
	{
		$user = $this->userModel->get_user_by_email($email);
		if ($user->resetrequesttime != NULL && $user->resetrequesttime > input_datetime('-1 day'))
		{
			$this->form_validation->set_message('reset_request_sent', lang('reset_request_sent'));
			return FALSE;
		}
		return TRUE;
	}

	/////////////////////////
	// Table
	/////////////////////////

	/**
	 *
	 * Specifies the user table
	 */
	public function table()
	{
		$this->datatables->select('username, role, email, phone, mobile, id');
		$this->datatables->from('user');

		$this->datatables->edit_column('username', '$1', 'user_get_link_by_id(id)');
		$this->datatables->edit_column('role', '$1', 'lang(role)');
		$this->datatables->edit_column('email', '$1', 'mailto(email)');
		$this->datatables->edit_column('id', '$1', 'user_actions(id)');

		echo $this->datatables->generate();
	}
}
