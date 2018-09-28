<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_newsletter_table'))
{
    /** Creates the table with dyslexia data */
    function create_newsletter_table($id = NULL)
    {
        $CI =& get_instance();
        base_table($id);
        $CI->table->set_heading(lang('parent'), lang('email'));
    }
}

if (!function_exists('newsletter_to_csv'))
{
    /** Creates a .csv-file from a list of ParticipantModels */
    function newsletter_to_csv($list)
    {
        // Retrieve the headers
        $headers = array(lang('parent'), lang('email'));

        // Add headers to the csv array (later used in fputscsv)
        $csv_array = array();
        $csv_array[] = $headers;

        // Generate array for each row and put in total array
        foreach ($list as $item)
        {
            $name = $item->parentfirstname .' '. $item->parentlastname;
            // Add row to csv array
            $csv_array[] = [$name, $item->email];
        }

        // Create a new output stream and capture the result in a new object
        $fp = fopen('php://output', 'w');
        ob_start();

        // Create a new row in the CSV file for every in the array
        foreach ($csv_array as $row)
        {
            fputcsv($fp, $row, ';');
        }

        // Capture the output as a string
        $csv = ob_get_contents();

        // Close the object and the stream
        ob_end_clean();
        fclose($fp);

        return $csv;
    }
}
