<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mail
{

    var $CI;
    var $_to_admin = False;
    var $_use_footer_buttons = True;
    var $_use_ending = True;
    var $_greeting = "";
    var $_ending = "";
    var $_ending_name = "";
    var $_disclaimer = "";
    var $_footer_links = array();


    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function prepare($to_admin=False)
    {
        $this->CI->email->clear();
        $this->CI->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
        $this->_to_admin = $to_admin;
        $this->_use_footer_buttons = True;
        $this->_use_ending = True;
        $this->_greeting = "";
        $this->_ending = lang('mail_default_ending');
        $this->_ending_name = lang('babylab_team');
        $this->_disclaimer = lang('mail_disclaimer');
        $this->_footer_links = $this->footer_links();
    }

    /**
     * Set the recepient email address.
     * Automatically overrides the email in development mode
     */
    public function to($to)
    {
        $this->CI->email->to(in_development() ? TO_EMAIL_OVERRIDE : $to);
    }

    public function bcc($bcc)
    {
        $this->CI->email->bcc(in_development() ? TO_EMAIL_OVERRIDE : $bcc);
    }

    /**
     * Set the subject of the mail. This string will be automatically preceded by the
     * string "Babylab Utrecht: "
     */
    public function subject($subject)
    {
        $this->CI->email->subject(sprintf("Babylab Utrecht: %s", $subject));
    }

    /** 
     * Set the name of the recipient. This is how the recipient will be addressed in the
     * greeting
     */
    public function to_name($greeting)
    {
        $this->_greeting = sprintf(lang('mail_heading'), $greeting);
    }

    /**
     * Set the greeting method
     */
    public function greeting($greeting)
    {
        $this->_greeting = $greeting;
    }

    /**
     * Don't show any of the footer buttons
     */
    public function no_footer_buttons()
    {
        $this->_use_footer_buttons = False;
    }

    /**
     * Do not use the mail template ending of the mail
     */
    public function no_ending()
    {
        $this->_use_ending = False;
    }

    /**
     * Override the default footer buttons. 
     * @arg array of arrays with "text" => string, "link" => string
     */
    public function set_footer_buttons($arr)
    {
        $this->_footer_links = $arr;
    }

    /**
     * Set the message content. This message will automatically be put
     * in the responsive email template. Make sure the greeting, ending
     * and disclaimer are not included in the message, as those are
     * appended seperately
     */
    public function message($msg)
    {
        $data['hello'] = $this->_greeting;
        $data['mail_ending'] = ($this->_use_ending) ? sprintf("%s,<br/>%s", $this->_ending, $this->_ending_name) : "";
        $data['disclaimer'] = $this->_disclaimer;
        $data['message'] = $msg;
        $data['footer_links'] = ($this->_use_footer_buttons) ? $this->_footer_links : array();

        $message = $this->CI->load->view('mail/mail', $data, TRUE);

        $this->CI->email->message($message);
    }

    /**
     * Set how to end the email
     * The format will be
     * <ending>,
     * <name>
     * If this function is not used, the default ending will be used
     */
    public function ending($ending, $name)
    {
        $this->_ending = $ending;
        $this->_ending_name = $name;
    }

    /**
     * Use this to add an attachment to the email
     */
    public function attach($attachment)
    {
        // $this->CI->email->attach($attachment);
    }

    /**
     * Use this to override the default disclaimer
     */
    public function disclaimer($disclaimer)
    {
        $this->_disclaimer = $disclaimer;
    }

    /**
     * Use this to send the email
     */
    public function send()
    {
        $this->CI->email->send();
    }

    /**
     * Method to generate an array of arrays for the linked buttons
     * in the footer of the mail.
     * If the e-mail is send to the admin, a button linking to the administration
     * interface is shown by default. Otherwise, a button linking to the selfservice
     * page is shown
     */
    private function footer_links()
    {
        $base_url = get_instance()->config->config['base_url'];
        $links = array();
        if($this->_to_admin)
        {
            array_push($links, array("text" => lang('administration_interface'), "link" => $base_url));
        } else {
            array_push($links, array("text" => "selfservice", "link" => $base_url . "selfservice"));
        }

        return $links;
    }
   
}
