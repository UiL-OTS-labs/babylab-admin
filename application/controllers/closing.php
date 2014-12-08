<?php
class Closing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->authenticate->redirect_except();
        reset_language(current_language());
    }

    public function index($include_past = FALSE)
    {
        $add_url = array('url' => 'closing/add', 'title' => lang('add_closing'));
        $past_url = closing_past_url($include_past);

        create_closing_table();
        $data['ajax_source'] = 'closing/table/' . $include_past;
        $data['page_title'] = lang('closings');
        $data['action_urls'] = array($add_url, $past_url);

        $this->load->view('templates/header', $data);
        $this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Leader);
        $this->load->view('templates/footer');
    }

    /** Deletes the specified impediment. */
    public function delete($closing_id)
    {
        $this->closingModel->delete_closing($closing_id);
        flashdata(lang('closing_deleted'));
        redirect($this->agent->referrer(), 'refresh');
    }

    /////////////////////////
    // Table
    /////////////////////////

    public function table($include_past = FALSE)
    {
        $this->datatables->select('name, from, comment, closing.id AS id, location_id');
        $this->datatables->from('closing');
        $this->datatables->join('location', 'location.id = closing.location_id');

        if (!$include_past) $this->db->where('to >=', input_date());

        $this->datatables->edit_column('name', '$1', 'location_get_link_by_id(location_id)');
        $this->datatables->edit_column('from', '$1', 'closing_dates_by_id(id)');
        $this->datatables->edit_column('comment', '$1', 'comment_body(comment, 30)');
        $this->datatables->edit_column('id', '$1', 'closing_actions(id)');

        $this->datatables->unset_column('location_id');

        echo $this->datatables->generate();
    }
}