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

if (!function_exists('comment_actions'))
{
	/** Possible actions for a comment: prioritize and delete */ 
	function comment_actions($comment_id) 
	{
		$CI =& get_instance();
		$c = $CI->commentModel->get_comment_by_id($comment_id);
		
		$prio_link = anchor('comment/prioritize/' . $c->id . ($c->priority ? '/0' : ''), img_star(!$c->priority));
		$d_link = anchor('comment/delete/' . $c->id, img_delete(), warning(lang('sure_delete_comment')));
		
		return $prio_link . ' ' . $d_link;
	}
}
