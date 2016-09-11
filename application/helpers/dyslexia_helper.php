<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_dyslexia_table'))
{
	/** Creates the table with dyslexia data */
	function create_dyslexia_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('participant'), lang('parent'), lang('statement'), lang('emt_score'), lang('klepel_score'), lang('vc_score'), lang('comment'), lang('actions'));
	}
}

if (!function_exists('dyslexia_actions'))
{
	/** Possible actions for a dyslexia: prioritize and delete */
	function dyslexia_actions($dyslexia_id)
	{
		$edit_link = anchor('dyslexia/edit/' . $dyslexia_id, img_edit());
		$delete_link = anchor('dyslexia/delete/' . $dyslexia_id, img_delete(), warning(lang('sure_delete_dyslexia')));

		return implode(' ', array($edit_link, $delete_link));
	}
}

if (!function_exists('dyslexia_check'))
{
	/** Checks whether participants whose parents are listed as dyslexic actually have a dyslexia item added to them */
	function dyslexia_check($participant)
	{
		$CI =& get_instance();

		$result = array();
		if ($participant->dyslexicparent)
		{
			$genders = str_split($participant->dyslexicparent);
			foreach ($genders AS $gender)
			{
				$dyslexia = $CI->dyslexiaModel->get_dyslexia_by_participant_gender($participant->id, $gender);
				if (!$dyslexia) 
				{
					array_push($result, sprintf(lang('verify_dyslexia'), lcfirst(gender_parent($gender)), name($participant), anchor('dyslexia/add/', lang('here'), array('target' => '_blank'))));
				}
			}
		}

		return $result;
	}
}

if (!function_exists('dyslexia_to_csv'))
{
	/** Creates a .csv-file from a list of DyslexiaModels */
	function dyslexia_to_csv($dyslexia_list, $experiment_id = NULL)
	{
		$CI =& get_instance();

		// Retrieve the headers
		$headers = array(lang('participant'), lang('parent'), lang('statement'), 
			lang('emt_score'), lang('klepel_score'), lang('vc_score'), lang('comment'));

		if ($experiment_id)
		{
			array_unshift($headers, lang('part_number'));
		}
		
		// Add headers to the csv array (later used in fputscsv)
		$csv_array = array();
		$csv_array[] = $headers;
		
		// Generate array for each row and put in total array
		foreach ($dyslexia_list as $dyslexia)
		{			
			$participant = $CI->dyslexiaModel->get_participant_by_dyslexia($dyslexia);
			
			$refnr = reference_number($participant);
			$s = $dyslexia->statement ? lang('yes') : lang('no');
			$csv_row = array($refnr, $dyslexia->gender, $s, 
				$dyslexia->emt_score, $dyslexia->klepel_score, $dyslexia->vc_score, $dyslexia->comment);

			if ($experiment_id) 
			{
				$participation = $CI->participationModel->get_participation($experiment_id, $participant->id);
				array_unshift($csv_row, $participation->part_number);
			}
			
			// Add row to csv array
			$csv_array[] = $csv_row;
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
