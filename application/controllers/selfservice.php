<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

    /** Submits the e-mail and redirects based on validation. */
    public function submit($language = L::Dutch)
    {
        // E-mail = NOT OK -> return to index with error message
        if (!$this->validate_email($language))
        {
            $this->index($language);
        }
        // E-mail = OK -> create a single-time login code, send an e-mail to the user with the link.
        else
        {
            $email = $this->input->post('email');

            $code = $language . '/' . bin2hex(openssl_random_pseudo_bytes(8));
            $update = array('selfservicecode' => $code, 'selfservicetime' => input_datetime('+1 day'));

            $participants = $this->participantModel->get_participants_by_email($email); 
            foreach ($participants as $p)
            {
                $this->participantModel->update_participant($p->id, $update);
                $parent_name = parent_name($p);
            }

            $message_data['name_parent'] = $parent_name;
            $message_data['url'] = 'selfservice/auth/' . $code;
            $message = $this->load->view('mail/selfservice', $message_data, TRUE);

            $this->email->clear();
            $this->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
            $this->email->to(in_development() ? TO_EMAIL_OVERRIDE : $email);
            $this->email->subject(lang('selfservice_mail_subject'));
            $this->email->message($message);
            $this->email->send();

            flashdata(sprintf(lang('selfservice_mail_sent'), $email));
            redirect('selfservice');
        }
    }

    /** Authenticates the single-login code. Returns true if authentication was successful. */
    public function auth($language, $selfservicecode)
    {
        $participants = $this->participantModel->get_participants_by_selfservicecode($language . '/' . $selfservicecode);

        // If there are participants found, and request is not too old... 
        if ($participants && $participants[0]->selfservicetime > input_datetime())
        {
            // Create a fresh, brand new session
            $this->session->sess_create();

            // Set session data
            $session_data = array(
                    'email'             => $participants[0]->email,
                    'participant_id'    => $participants[0]->id,
                    'language'          => $language,
                    'selfservice'       => TRUE,
            );
            $this->session->set_userdata($session_data);

            // Login was successful
            redirect('selfservice/welcome');
        }
        // If there is no database result found, destroy the session
        else
        {
            flashdata(lang('selfservice_incorrect_url'), FALSE);
            redirect('selfservice');
        }
    }

    /** Shows the welcome page with actions to change participants. */
    public function welcome() 
    {
        if (!$this->session->userdata('selfservice'))
        {
            flashdata(lang('selfservice_incorrect_url'), FALSE);
            redirect('selfservice');
        }

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

    /** Submits the username and password and redirects based on validation. */
    public function welcome_submit()
    {
        // If register button is clicked, go to registration
        if ($this->input->post('register'))
        {
            redirect(current_language() == L::Dutch ? 'aanmelden' : 'signup', 'refresh');
        }
        // Else, run validation
        else 
        {
            if (!$this->validate_participant())
            {
                // If not succeeded, show form again with error messages
                $this->welcome();
            }
            else
            {
                // If succeeded, update the participants
                $participant = $this->post_participant();

                $participants = $this->participantModel->get_participants_by_email(current_email()); 
                foreach ($participants as $p)
                {
                    $activate = $this->input->post('active_' . $p->id); 
                    if ($p->activated && !$activate)
                    {
                        $this->participantModel->deactivate($p->id, DeactivateReason::SelfService);
                    }
                    else if (!$p->activated && $activate)
                    {
                        $this->participantModel->activate($p->id);
                    }

                    $participant['otherbabylabs'] = $this->input->post('other_' . $p->id); 
                    $this->participantModel->update_participant($p->id, $participant);
                }

                // Display success
                flashdata(lang('selfservice_edit_success'));
                redirect('selfservice/welcome', 'refresh');
            }
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

    /** Validates an e-mail */
    public function validate_email($language)
    {
        reset_language($language);

        // Set validation rules
        $this->form_validation->set_rules('email', lang('email'), 'trim|required|valid_email|callback_participant_exists');

        return $this->form_validation->run();
    }

    /** Validates a participant */
    private function validate_participant()
    {
        $this->form_validation->set_rules('parentfirstname', lang('parentfirstname'), 'trim|required');
        $this->form_validation->set_rules('parentlastname', lang('parentlastname'), 'trim');
        $this->form_validation->set_rules('city', lang('city'), 'trim');
        $this->form_validation->set_rules('phone', lang('phone'), 'trim|required');
        $this->form_validation->set_rules('phonealt', lang('phonealt'), 'trim');
        $this->form_validation->set_rules('email', lang('email'), 'trim|valid_email');

        return $this->form_validation->run();
    }

    /** Posts the data for a participant */
    private function post_participant()
    {
        return array(
                'parentfirstname'       => $this->input->post('parentfirstname'),
                'parentlastname'        => $this->input->post('parentlastname'),
                'city'                  => $this->input->post('city'),
                'phone'                 => $this->input->post('phone'),
                'phonealt'              => $this->input->post('phonealt'),
                'email'                 => $this->input->post('email'),
        );
    }

    /////////////////////////
    // Callbacks
    /////////////////////////

    /** Checks whether participants exists for a given e-mail address. */
    public function participant_exists($email)
    {
        $participants = $this->participantModel->get_participants_by_email($email);
        if (!$participants)
        {
            $this->form_validation->set_message('participant_exists', lang('unknown_email'));
            return FALSE;
        }
        return TRUE;
    }
}
