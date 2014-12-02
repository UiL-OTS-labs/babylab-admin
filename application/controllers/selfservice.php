<?php
class SelfService extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        reset_language(current_language());

        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
    }

    /////////////////////////
    // Logging in/out
    /////////////////////////

    /** Specifies the contents of the default page. */
    public function index($language = L::Dutch)
    {
        reset_language($language);

        $data['page_title'] = lang('login');
        $data['current_language'] = $language;
        
        $this->load->view('templates/register_header', $data);
        $this->load->view('selfservice/login', $data);
        $this->load->view('templates/footer');
    }

    /** Submits the username and password and redirects based on validation. */
    public function submit($language = L::Dutch)
    {
        // E-mai = NOT OK -> return to index
        if (!$this->validate($language))
        {
            $this->index($language);
            return;
        }
        // E-mail = OK -> create a single-time login code, send an e-mail to the user with the link.
        else
        {
            if ($this->authenticate($language)) redirect('selfservice/welcome');
            else $this->index($language);
        }
    }

    public function welcome() 
    {
        $participants = $this->participantModel->get_participants_by_email(current_email());
        $first = $participants[0];

        $data['page_title'] = lang('login');
        $data['current_language'] = current_language();
        $data['participants'] = $participants;
        $data['participant'] = $first;
        $data = add_fields($data, 'participant', $first);
        
        $this->load->view('templates/register_header', $data);
        $this->load->view('selfservice/welcome', $data);
        $this->load->view('templates/footer');
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
        $this->form_validation->set_rules('email', lang('email'), 'trim|required|valid_email');

        return $this->form_validation->run();
    }

    /////////////////////////
    // Callbacks
    /////////////////////////

    /** Authenticates username and password. Returns true if authentication was successful. */
    public function authenticate($language)
    {
        $email = $this->input->post('email');

        $participants = $this->participantModel->get_participants_by_email($email);

        // If there are participants found in DB...
        if ($participants)
        {
            // Destroy old session
            $this->session->sess_destroy();

            // Create a fresh, brand new session
            $this->session->sess_create();

            // Set session data
            $session_data = array(
                    'email'     => $email,
                    'language'  => $language,
            );
            $this->session->set_userdata($session_data);

            // Login was successful
            return TRUE;
        }
        // If there is no database result found, destroy the session
        else
        {
            $this->session->sess_destroy();
            return FALSE;
        }
    }
}
