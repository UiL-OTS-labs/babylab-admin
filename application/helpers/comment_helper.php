<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_comment_table'))
{
	/** Creates the table with comment data */
	function create_comment_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('participant'), lang('comment'), lang('posted_at'), lang('posted_by'), lang('actions'));
	}
}

if (!function_exists('comment_body'))
{
	/** Comment body output: first 75 characters */
	function comment_body($comment, $max_length = 75)
	{
		if (strlen($comment) > $max_length) 
		{
			$abbr = '<abbr title="' . $comment . '">';
			$date_output = substr($comment, 0, $max_length) . '...';
			$abbr_end = '</abbr>';
			return $abbr . $date_output . $abbr_end;
		}
		else
		{
			return $comment;
		}
	}
}

if (!function_exists('comment_actions'))
{
	/** Possible actions for a comment: prioritize and delete */
	function comment_actions($comment_id)
	{
		$CI =& get_instance();
		$c = $CI->commentModel->get_comment_by_id($comment_id);

		$prio_link = anchor('comment/prioritize/' . $comment_id . ($c->priority ? '/0' : ''), img_star(!$c->priority));
		$edit_link = anchor('comment/edit/' . $comment_id, img_edit());
		$d_link = anchor('comment/delete/' . $comment_id, img_delete(), warning(lang('sure_delete_comment')));

		return implode(' ', array($prio_link, $edit_link, $d_link));
	}
}
