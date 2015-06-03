<?php
class ClosingModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /////////////////////////
    // CRUD-actions
    /////////////////////////

    /** Returns all closings as an array */
    public function get_all_closings()
    {
        return $this->db->get_where('closing')->result();
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
    // Helpers
    /////////////////////////

    /** Returns whether there is already an closing for the given date and user */
    public function within_bounds($date, $location_id)
    {
        $this->db->where('location_id', $location_id);
        $this->db->where('from <=', $date);
        $this->db->where('to >=', $date);
        $this->db->from('closing');
        return $this->db->count_all_results() > 0;
    }
}