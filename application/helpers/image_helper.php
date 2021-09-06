<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('img_delete'))
{
	/** Returns the delete image */
	function img_delete($opaque = FALSE)
	{
		return img(array(
			'src' => 'images/delete.png', 
			'title' => lang('delete'),
			'style' => ($opaque ? 'opacity:0.3' : '')
		));
	}
}

if (!function_exists('img_p_delete'))
{
	/** Returns the delete image for participations */
	function img_p_delete()
	{
		return img(array(
			'src' => 'images/delete.png', 
			'title' => lang('delete_participation'),
		));
	}
}

if (!function_exists('img_edit'))
{
	/** Returns the edit image */
	function img_edit($opaque = FALSE)
	{
		return img(array(
				'src' => 'images/pencil.png',
				'title' => lang('edit'),
				'style' => ($opaque ? 'opacity:0.3' : '')
		));
	}
}

if (!function_exists('img_call'))
{
	/** Returns the call image */
	function img_call()
	{
		return 	img(array(
				'src' => 'images/phone.png',
				'title' => lang('call_participants'),
		));
	}
}

if (!function_exists('img_call_p'))
{
	/** Returns the call image for a single participant */
	function img_call_p($participant, $experiment_id, $weeks_ahead = WEEKS_AHEAD)
	{
		// Check if the participant is currently locked
		$CI =& get_instance();
		$participation = $CI->participationModel->get_participation($experiment_id, $participant->id);

		if ($CI->participationModel->is_locked($participation)) 
		{
			$current_call = $CI->callModel->last_call($participation->id);
			$user = $CI->callModel->get_user_by_call($current_call->id);
			$img = img(array(
					'src' => 'images/phone_sound.png',
					'title' => sprintf(lang('in_conversation'), name($participant))
			));
			$warning = warning(sprintf(lang('take_over_warning'), name($participant), $user->username));
			
			$result = anchor('call/take_over/' . $current_call->id, $img, $warning);
		}
		else 
		{
			$img = img(array(
						'src' => 'images/phone.png',
						'title' => sprintf(lang('call_participant'), name($participant))
			));
			$result = anchor('participation/call/' . $participant->id . '/' . $experiment_id . '/'. $weeks_ahead, $img);
		}
		
		return $result;
	}
}

if (!function_exists('img_participations'))
{
	/** Returns the image for participations */
	function img_participations($nr_participants)
	{
		return img(array(
				'src' => 'images/group.png',
				'title' => lang('participations') . ': ' . $nr_participants,
				'style' => ($nr_participants == 0 ? 'opacity:0.3' : '')
		));
	}
}

if (!function_exists('img_comment'))
{
	/** Returns the image for a comment */
	function img_comment()
	{
		return img(array(
				'src' => 'images/comments.png',
				'title' => lang('comment'),
		));
	}
}

if (!function_exists('img_comments'))
{
	/** Returns the image for comments */
	function img_comments($nr_comments = 0)
	{
		return img(array(
				'src' => 'images/comments.png',
				'title' => lang('comments') . ' (' . $nr_comments . ')',
				'style' => ($nr_comments == 0 ? 'opacity:0.3' : '')
		));
	}
}

if (!function_exists('img_tick'))
{
	/** Returns a (centered) tick image if $value == 1, otherwise an empty string. */
	function img_tick($value, $show_image = TRUE)
	{
		return $value 
			? img(array('src' => 'images/tick.png', 'class' => 'center')) 
			: ($show_image ? img(array('src' => 'images/cross.png', 'class' => 'center')) : '');
	}
}

if (!function_exists('img_tick_null'))
{
	/** Returns a (centered) tick image if $value == 1, otherwise an empty string. */
	function img_tick_null($value, $show_image = TRUE)
	{
	    if ($value == null)
	        return "<div class='center'>?</div>";
		return $value
			? img(array('src' => 'images/tick.png', 'class' => 'center'))
			: ($show_image ? img(array('src' => 'images/cross.png', 'class' => 'center')) : '');
	}
}

if (!function_exists('img_accept'))
{
	/** Returns the accept image with the specified title. */
	function img_accept($title, $opaque = FALSE)
	{
		return img(array(
				'src' => 'images/accept.png',
				'title' => $title,
				'style' => ($opaque ? 'opacity:0.3' : '')
		));
	}
}

if (!function_exists('image_cancel'))
{
	/** Returns the cancel image with the specified title. */
	function img_cancel($title, $opaque = FALSE)
	{
		return img(array(
				'src' => 'images/cancel.png',
				'title' => $title,
				'style' => ($opaque ? 'opacity:0.3' : '')
		));
	}
}

if (!function_exists('img_noshow'))
{
	/** Returns the no-show image. */
	function img_noshow($opaque = FALSE)
	{
		return img(array(
				'src' => 'images/date_delete.png',
				'title' => lang('no_show'),
				'style' => ($opaque ? 'opacity:0.3' : '')
		));
	}
}

if (!function_exists('img_star'))
{
	/** Returns the star image (for setting priorities). */
	function img_star($prio = TRUE)
	{
		return img(array(
				'src' => 'images/star.png',
				'title' => lang('priority'),
				'style' => ($prio ? 'opacity:0.5' : '')
		));
	}
}

if (!function_exists('img_calendar'))
{
	/** Returns the calendar image */
	function img_calendar($opaque = FALSE)
	{
		return img(array(
				'src' => 'images/calendar.png',
				'title' => lang('set_date'),
				'style' => ($opaque ? 'opacity:0.3' : '')
		));
	}
}

if (!function_exists('img_active'))
{
	/** Returns the active image */
	function img_active($active)
	{
		return img(array(
				'src' => $active ? 'images/status_online.png' : 'images/status_busy.png',
				'title' => $active ? lang('deactivate') : lang('activate')
		));
	}
}

if (!function_exists('img_edit_participant'))
{
	/** Returns the edit participant image */
	function img_edit_participant($participant)
	{
		return img(array(
				'src' => 'images/status_online.png',
				'title' => sprintf(lang('edit_participant'), name($participant))
		));
	}
}

if (!function_exists('img_scores'))
{
	/** Returns the scores image */
	function img_scores($opaque = FALSE)
	{
		return img(array(
				'src' => 'images/report.png',
				'title' => lang('scores'),
				'style' => ($opaque ? 'opacity:0.3' : '')
		));
	}
}

if (!function_exists('img_zoom'))
{
	/** Returns the zoom image */
	function img_zoom($item)
	{
		return img(array(
				'src' => 'images/zoom.png',
				'title' => lang('inspect') . ' ' . lcfirst(lang($item))
		));
	}
}

if (!function_exists('img_email'))
{
	/** Returns the email image */
	function img_email($title, $opaque = FALSE)
	{
		return img(array(
				'src' => 'images/email.png',
				'title' => $title,
				'style' => ($opaque ? 'opacity:0.3' : '')
		));
	}
}