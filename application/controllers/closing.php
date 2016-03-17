<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Closing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->authenticate->redirect_except();
        reset_language(current_language());

        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
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
        $this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
        $this->load->view('templates/footer');
    }

    /** Page to add a closing */
    public function add()
    {
        $data['page_title'] = lang('closings');
        $data['locations'] = location_options($this->locationModel->get_all_locations());
        
        $this->load->view('templates/header', $data);
        $this->authenticate->authenticate_redirect('closing_add_view', $data, UserRole::Admin);
        $this->load->view('templates/footer');
    }

    /** Adds a closing for a participant */
    public function add_submit()
    {
        // Run validation
        if (!$this->validate_closing())
        {
            // Show form again with error messages
            $this->add();
        }
        else
        {
            // If succeeded, insert data into database
            $closing = $this->post_closing();

            foreach($closing as $closed)
            {
                $this->closingModel->add_closing($closed);
            }

            flashdata(lang('closing_added'));
            redirect('/closing', 'refresh');
        }
    }

    /** Deletes the specified closing. */
    public function delete($closing_id)
    {
        $this->closingModel->delete_closing($closing_id);
        flashdata(lang('closing_deleted'));
        redirect($this->agent->referrer(), 'refresh');
    }

    /////////////////////////
    // Form handling
    /////////////////////////

    /** Validates an closing */
    private function validate_closing()
    {
        // TODO: validate that either location or lockdown has been selected
        $this->form_validation->set_rules('location', lang('location'), 'callback_not_zero');
        $this->form_validation->set_rules('lockdown', lang('lockdown'), 'trim');
        $this->form_validation->set_rules('all_day', lang('all_day'), 'trim');
        $this->form_validation->set_rules('from_date', lang('from_date'), 'trim|callback_daterange_required|callback_check_within_bounds');
        $this->form_validation->set_rules('to_date', lang('to_date'), 'trim|callback_daterange_required|callback_check_within_bounds');
        $this->form_validation->set_rules('date', lang('date'), 'trim|callback_date_required|callback_check_within_bounds');
        $this->form_validation->set_rules('comment', lang('comment'), 'trim');

        return $this->form_validation->run();
    }

    /** Posts the data for an closing */
    private function post_closing()
    {
        $postingdata = array();

        $lockdown   = $this->input->post('lockdown') === '1';
        $locations  = ($lockdown) ? array(null) : $this->input->post('location');
        $all_day     = $this->input->post('all_day') === '1';
        if($all_day){
            $from       = input_datetime($this->input->post('date'));
            $to         = input_datetime($this->input->post('date') . " 23:59");
        } else {
            $from       = input_datetime($this->input->post('from_date'));
            $to         = input_datetime($this->input->post('to_date'));
        }
        $comment    = $this->input->post('comment');
        

        if($locations){
            foreach($locations as $location)
            {
                array_push($postingdata, array(
                        'location_id'   => $location,
                        'from'          => $from,
                        'to'            => $to,
                        'comment'       => $comment,
                    ));
            }    
        }
        
        return $postingdata;
            
    }

    /////////////////////////
    // Callbacks
    /////////////////////////

    /** Checks whether the given date is within bounds of an existing closing for this location */
    public function check_within_bounds($date)
    {
        $locations = $this->input->post('lockdown') === '1' ? array(null) : $this->input->post('location');
        if($locations)
        {
            foreach($locations as $location_id)
            {
                // $location_id = $this->input->post('location');
                if ($this->closingModel->within_bounds(input_datetime($date), $location_id))
                {
                    $this->form_validation->set_message('check_within_bounds', lang('closing_within_bounds'));
                    return FALSE;
                }
            }
        }
        
        return TRUE;
    }

    /** Checks whether the given parameter is valid (if at least one location is set) */
    public function not_zero($values)
    {
        if (!$values && $this->input->post('lockdown') !== '1')
        {
            $this->form_validation->set_message('not_zero', lang('isset'));
            return FALSE;
        }
        return TRUE;
    }

    public function daterange_required($values)
    {
        if(!$values && $this->input->post('all_day') !== '1')
        {
            $this->form_validation->set_message('daterange_required', lang('isset'));
            return FALSE;
        }
        return TRUE;
    }

    public function date_required($values)
    {
        if(!$values && $this->input->post('all_day') === '1')
        {
            $this->form_validation->set_message('date_required', lang('isset'));
            return FALSE;
        }
        return TRUE;
    }

    /////////////////////////
    // Table
    /////////////////////////
    
    public function table($include_past = FALSE)
    {
        $this->datatables->select('name, from, comment, closing.id AS id, location_id');
        $this->datatables->from('closing');
        $this->datatables->join('location', 'location.id = closing.location_id', 'LEFT');

        if (!$include_past) $this->db->where('to >=', input_date());

        $this->datatables->edit_column('name', '$1', 'closing_location_link_by_id(location_id)');
        $this->datatables->edit_column('from', '$1', 'closing_dates_by_id(id)');
        $this->datatables->edit_column('comment', '$1', 'comment_body(comment, 30)');
        $this->datatables->edit_column('id', '$1', 'closing_actions(id)');

        $this->datatables->unset_column('location_id');

        echo $this->datatables->generate();
    }
}
