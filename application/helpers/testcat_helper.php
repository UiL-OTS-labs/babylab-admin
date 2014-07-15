<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_testcat_table'))
{
	/** Creates the table with testcat data */
	function create_testcat_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('name'), lang('actions'));
		if (empty($id)) array_unshift($heading, lang('test'));
		$CI->table->set_heading($heading);
	}
}

/////////////////////////
// Options
/////////////////////////

if (!function_exists('testcat_options'))
{
	/** Returns an option list of testcats */
	function testcat_options($testcats, $empty = TRUE)
	{
		$CI =& get_instance();
		$t_options = array();
		foreach ($testcats as $t)
		{
			//$pre = $CI->testCatModel->has_parent($t) ? '&#x251c;&#x2500;' : '';
			$t_options[$t->id] = testcat_code_name($t);
		}
		asort($t_options);
		return $empty ? array('null' => lang('none')) + $t_options : $t_options;
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('testcat_get_link'))
{
	/** Returns the get link for a testcat */
	function testcat_get_link($testcat)
	{
		return anchor('testcat/get/' . $testcat->id, testcat_code_name($testcat));
	}
}

if (!function_exists('testcat_get_link_by_id'))
{
	/** Returns the get link for a testcat */
	function testcat_get_link_by_id($testcat_id)
	{
		$CI =& get_instance();
		$testcat = $CI->testCatModel->get_testcat_by_id($testcat_id);

		return testcat_get_link($testcat);
	}
}

if (!function_exists('testcat_actions'))
{
	/** Possible actions for a testcat: prioritize and delete */ 
	function testcat_actions($testcat_id) 
	{
		$CI =& get_instance();
		$scores = $CI->scoreModel->get_scores_by_testcat($testcat_id);

		$edit_link = anchor('testcat/edit/' . $testcat_id, img_edit());
		$score_link = count($scores) > 0 ? anchor('score/testcat/' . $testcat_id, img_scores()) : img_scores(TRUE);
		$delete_link = anchor('testcat/delete/' . $testcat_id, img_delete(), warning(lang('sure_delete_testcat')));
		
		return implode(' ', array($edit_link, $score_link, $delete_link));	
	}
}

/////////////////////////
// Others
/////////////////////////

if (!function_exists('testcat_name'))
{
	/** Returns the name of a test category given the id */
	function testcat_name($testcat_id)
	{
		$CI =& get_instance();
		$testcat = $CI->testCatModel->get_testcat_by_id($testcat_id);
		return $testcat->name;
	}
}

if (!function_exists('testcat_code_name'))
{
	/** Returns the code + name of a test category */
	function testcat_code_name($testcat)
	{
		return $testcat->code . ' - ' . $testcat->name;
	}
}

if (!function_exists('testcat_score_boxplot'))
{
	function testcat_score_boxplot($testcat_id)
	{
		$CI =& get_instance();
		$scores = $CI->scoreModel->get_scores_by_testcat($testcat_id);
		$score_array = get_object_ids($scores, 'score');
		return '<div class="boxplot">' . implode(',', $score_array) . '</div>';
	}
}
