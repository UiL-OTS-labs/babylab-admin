<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newsletter extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->authenticate->redirect_except();
        reset_language(current_language());
    }

    /////////////////////////
    // CRUD-actions
    /////////////////////////

    /** Specifies the contents of the default page. */
    public function index()
    {
        if (current_role() !== UserRole::ADMIN)
        {
            flashdata(lang('not_authorized'));
            redirect('/welcome/', 'refresh');
        }

        $download_url = array('url' => 'newsletter/download', 'title' => lang('download_newsletter'));

        create_newsletter_table();
        $data['ajax_source'] = 'newsletter/table/';
        $data['page_title'] = lang('newsletter');
        $data['action_urls'] = array($download_url);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/list_view', $data);
        $this->load->view('templates/footer');
    }

    /////////////////////////
    // Other views
    /////////////////////////

    /**
     * Downloads all newsletter receivers as a .csv-file.
     */
    public function download()
    {
        if (current_role() !== UserRole::ADMIN)
        {
            flashdata(lang('not_authorized'));
            redirect('/welcome/', 'refresh');
        }

        $csv = newsletter_to_csv($this->participantModel->get_participants_with_newsletter());

        // Generate filename
        $filename = 'newsletter_' . mdate("%Y%m%d_%H%i", time()) . '.csv';

        // Download the file
        force_download($filename, $csv);
    }

    /////////////////////////
    // Table
    /////////////////////////

    public function table()
    {
        $this->datatables->select('CONCAT(parentfirstname, " ", parentlastname) AS p, email ', FALSE);
        $this->datatables->from('participant');
        $this->datatables->where('newsletter = true');
        $this->datatables->group_by('parentfirstname, parentlastname, email');


        $this->datatables->unset_column('participant_id');

        echo $this->datatables->generate();
    }
}
