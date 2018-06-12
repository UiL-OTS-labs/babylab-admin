<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except(array('index', 'submit'));
		reset_language(current_language());

		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}

	/////////////////////////
	// Logging in/out
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index($language = L::DUTCH)
	{
		// If logged in, redirect to the home pages per role
		if (current_user_id() > 0)
		{
			redirect('welcome');
		}
		// Else, reset the language settings and open the login form
		else
		{
			reset_language($language);

			$data['page_title'] = lang('login');
			$data['language'] = $language;
			if ($this->session->userdata('redirect_back'))
			{
				$data['referrer'] = $this->session->userdata('redirect_back');  // grab value and put into a temp variable so we unset the session value
				$this->session->unset_userdata('redirect_back');
			}

			$this->load->view('templates/header', $data);
			$this->load->view('login_view', $data);
			$this->load->view('templates/footer');
		}
	}

	/** Submits the username and password and redirects based on validation. */
	public function submit($language = L::DUTCH)
	{
		// Login = NOT OK -> destroy session and return to login form
		if (!$this->validate($language))
		{
		    if(session_exists())
			    $this->session->sess_destroy();
			$this->index($language);
			return;
		}
		// Login = OK -> redirect
		else
		{
			// Load language file
			reset_language(current_language());

			// If a someone has not yet signed, send him to the contract
			$user = $this->userModel->get_user_by_id(current_user_id());
			if ($user->needssignature && !$user->signed)
			{
				redirect('user/sign/');
			}

			// Otherwise, check if we have a referrer, if so, send to that page
			$referrer = $this->input->post('referrer');
			if ($referrer)
			{
				redirect($referrer);
			}
			// Otherwise, redirect to welcome page for user role
			else
			{
				redirect('welcome');
			}
		}
	}

	/** Logs out the current user by destroying the session. Returns to login page. */
	public function logout()
	{
		$language = current_language();
		if(session_exists())
		    $this->session->sess_destroy();
		redirect($language == L::DUTCH ? 'inloggen' : 'login');
	}

	/** Switches the role of the current user, if he has that privilege. Returns to the welcome page. */
	public function switch_to($role)
	{
		if (user_role() === UserRole::ADMIN && in_array($role, array(UserRole::ADMIN, UserRole::LEADER, UserRole::CALLER)))
		{
			$this->session->set_userdata(array('role' => $role));
			redirect('welcome');
		}
		else if (user_role() === UserRole::LEADER && in_array($role, array(UserRole::LEADER, UserRole::CALLER)))
		{
			$this->session->set_userdata(array('role' => $role));
			redirect('welcome');
		}
		else
		{
			show_error('Sorry, you are not allowed to do this.');
		}
	}

	/////////////////////////
	// Form validation
	/////////////////////////

	/** Validates username and password. */
	public function validate($language)
	{
		reset_language($language);

		// Set validation rules
		$this->form_validation->set_rules('username', lang('username'), 'trim|required|callback_authenticate');
		$this->form_validation->set_rules('password', lang('password'), 'trim|required');

		// Rewrite error messages
		$this->form_validation->set_message('authenticate', lang('invalid_login'));

		return $this->form_validation->run();
	}

	/////////////////////////
	// Callbacks
	/////////////////////////

	/** Authenticates username and password. Returns true if authentication was successful. */
	public function authenticate()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		try
        {
            $user = $this->userModel->get_user_by_username($username);
        } catch (\Exception $e)
        {
            // If there was an error, we can assume it's a lost cause
            return FALSE;
        }

		// If username found in DB...
		if ($user)
		{
			// Check against password and if activated
			if (!$this->phpass->check($password, $user->password) || !is_activated($user))
			{
			    if(session_exists())
				    $this->session->sess_destroy();

				return FALSE;
			}
			else
			{
				// Regenerate session, if one already exists. This will ensure a clean session
                if(session_exists())
				    $this->session->sess_regenerate();

				// Remove the password field
				unset($user->password);

				// Set role to leader if researcher (to simplify checking later on)
				$role = $user->role === UserRole::RESEARCHER ? UserRole::LEADER : $user->role;

				// Set session data
				$session_data = array(
					'username' => $username,
					'user_id' => $user->id,
					'user_role' => $user->role, // Stays the same
					'role' => $role, // Might be changed when user switches role
					'logged_in' => TRUE,
					'language' => user_language($user)
				);
				$this->session->set_userdata($session_data);

				// Login was successful
				return TRUE;
			}
		}
		// If there is no database result found, destroy the session
		else
		{
		    if(session_exists())
			    $this->session->sess_destroy();
			return FALSE;
		}
	}

}
