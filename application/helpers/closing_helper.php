<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_closing_table'))
{
    /** Creates the table with closing data */
    function create_closing_table($id = NULL)
    {
        $CI =& get_instance();
        base_table($id);
        $CI->table->set_heading(lang('location'), lang('closing'), lang('comment'), lang('actions'));
    }
}

if (!function_exists('closing_dates'))
{
    /** Gets the closing dates in readable format */
    function closing_dates($closing)
    {
        return output_datetime($closing->from) . ' - ' . output_datetime($closing->to);
    }
}

if (!function_exists('closing_dates_by_id'))
{
    /** Gets the closing dates in readable format */
    function closing_dates_by_id($closing_id)
    {
        $CI =& get_instance();
        $closing = $CI->closingModel->get_closing_by_id($closing_id);

        return closing_dates($closing);
    }
}

if (!function_exists('closing_actions'))
{
    /** Possible actions for an closing: delete */
    function closing_actions($closing_id)
    {
        return anchor('closing/delete/' . $closing_id, img_delete(), warning(lang('sure_delete_closing')));
    }
}

if (!function_exists('closing_past_url'))
{
    /** The "past" url for an closing */
    function closing_past_url($include_past)
    {
        $include_past_url = array('url' => 'closing/index/1', 'title' => lang('closing_include_past'));
        $dont_include_past_url = array( 'url' => 'closing/index/0', 'title' => lang('closing_exclude_past'));

        return $include_past ? $dont_include_past_url : $include_past_url;
    }
}

if (!function_exists('closing_location_link_by_id'))
{
    function closing_location_link_by_id($location_id)
    {
         if(isset($location_id)){
            return location_get_link_by_id($location_id);
        } else {
            return lang('lockdown');
        }
    }
}