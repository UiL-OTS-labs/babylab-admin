<?php
class closingModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /////////////////////////
    // CRUD-actions
    /////////////////////////

    /** Returns all closings as an array.
     * If $future is set to false, past closings are also included. */
    public function get_all_closings($future = TRUE)
    {
        if ($future) $this->db->where('to >=', input_date());
        return $this->db->get('closing')->result();
    }

    /** Adds an closing to the DB */
    public function add_closing($closing)
    {
        $this->db->insert('closing', $closing);
        return $this->db->insert_id();
    }

    /** Deletes an closing from the DB */
    public function delete_closing($closing_id)
    {
        $this->db->delete('closing', array('id' => $closing_id));
    }

    /** Returns the closing for an id */
    public function get_closing_by_id($closing_id)
    {
        return $this->db->get_where('closing', array('id' => $closing_id))->row();
    }


    /////////////////////////
    // User actions
    /////////////////////////


    /** Returns all future closings for a user */
    public function get_future_closings_by_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('to >=', input_date());
        return $this->db->get('closing')->result();
    }

    public function get_future_closings_by_users($ids)
    {
        $this->db->where_in('user_id', $ids);
        $this->db->where('to >=', input_date());
        return $this->db->get('closing')->result();
    }

    public function get_closings_by_users($ids)
    {
        $this->db->where_in('user_id', $ids);
        $this->db->orderby('from');
        return $this->db->get('closing')->result();
    }

    /** Returns all availabilities for a user */
    public function get_closings_by_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->get('closing')->result();
    }

    /** Returns all availabilities for leaders of the experiments */
    public function get_closings_by_experiments($ids)
    {
        $this->db->select('closing.*');
        $this->db->join('leader', 'leader.user_id_leader = closing.user_id');
        $this->db->where_in('leader.experiment_id', $ids);
        return $this->db->get('closing')->result();
    }

    /** Returns the user for an closing */
    public function get_user_by_closing($closing)
    {
        return $this->db->get_where('user', array('id' => $closing->user_id))->row();
    }

    /** Returns whether there is already an closing for the given date and user */
    public function within_bounds($date, $user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('from <=', $date);
        $this->db->where('to >=', $date);
        $this->db->from('closing');
        return $this->db->count_all_results() > 0;
    }
}