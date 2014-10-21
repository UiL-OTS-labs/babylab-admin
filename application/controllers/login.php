<?php
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
	public function index($language = L::Dutch)
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
	public function submit($language = L::Dutch)
	{
		// Login = NOT OK -> destroy session and return to login form
		if ($this->validate($language) == FALSE)
		{
			$this->session->sess_destroy();
			$this->index($language);
			return;
		}
		// Login = OK -> redirect
		else
		{
			// Load language file
			reset_language(current_language());

			// Where to go now?
			$referrer = $this->input->post('referrer');
			
			// If user was sent to login from different page, redirect to
			// that page after login
			if (isset($referrer) && $referrer != '') redirect($referrer);
			
			// Otherwise, redirect to welcome page for user role
			else redirect('welcome');
		}
	}

	/** Logs out the current user by destroying the session. Returns to login page. */
	public function logout()
	{
		$language = current_language();
		$this->session->sess_destroy();
		redirect($language == L::Dutch ? 'inloggen' : 'login');
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

		$user = $this->userModel->get_user_by_username($username);

		// If username found in DB...
		if (!empty($user))
		{
			// Check against password and if activated
			if (!$this->phpass->check($password, $user->password) || !is_activated($user))
			{
				$this->session->sess_destroy();
				return FALSE;
			}
			else
			{
				// Destroy old session
				$this->session->sess_destroy();

				// Create a fresh, brand new session
				$this->session->sess_create();

				// Remove the password field
				unset($user->password);

				// Set session data
				$session_data = array(
						'username' 	=> $username,
						'user_id' 	=> $user->id,
						'logged_in' => TRUE,
						'role' 		=> $user->role,
						'language' 	=> user_language($user)
				);
				$this->session->set_userdata($session_data);

				// Login was successful
				return TRUE;
			}
		}
		// If there is no database result found, destroy the session
		else
		{
			$this->session->sess_destroy();
			return FALSE;
		}
	}
}
