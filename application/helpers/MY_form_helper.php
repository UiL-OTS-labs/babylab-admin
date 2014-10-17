<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('form_input_and_label'))
{
	function form_input_and_label($name, $value = '', $extra = '', $is_password = FALSE, $div = 'pure-control-group')
	{
		$extra = $extra . ' placeholder="' . lang($name) . '" id="' . $name . '"';
		$value = !empty($value) || $value == 0 ? $value : '';

		$div_start = empty($div) ? '' : '<div class="' . $div . '">';
		$label = form_label(lang($name), $name);
		$input = $is_password ? form_password($name, set_value($name, $value), $extra) : form_input($name, set_value($name, $value), $extra);
		$error_box = form_error($name);
		$div_end = empty($div) ? '' : '</div>';

		return $div_start . $label . $input . $error_box . $div_end;
	}
}

if (!function_exists('form_colorpicker'))
{
	function form_colorpicker($name, $value='', $extra = '', $div = 'pure-control-group')
	{
		$extra = $extra . ' placeholder="' . lang($name) . '" id="' . $name . '"' . ' style="display: none;"';
		$value = !empty($value) || $value == 0 ? $value : '';

		$div_start = empty($div) ? '' : '<div class="' . $div . '">';
		$label = form_label(lang($name), $name);
		$error_box = form_error($name);
		$div_end = empty($div) ? '' : '</div>';
		$input = "<label id='colorselector2'><label></label></label>";
		$input .= form_input($name, set_value($name, $value), $extra);
		return $div_start . $label . $input . $error_box . $div_end;
	}
}

if (!function_exists('form_textarea_and_label'))
{
	function form_textarea_and_label($name, $value = '', $label = '', $extra = '', $div = 'pure-control-group')
	{
		$label = empty($label) ? lang($name) : $label;
		$extra = $extra . ' placeholder="' . $label . '" id="' . $name . '"';
		$value = !empty($value) || $value == 0 ? $value : '';

		$div_start = empty($div) ? '' : '<div class="' . $div . '">';
		$label = form_label($label, $name);
		$error_box = form_error($name);
		$input = form_textarea($name, set_value($name, $value), $extra);
		$div_end = empty($div) ? '' : '</div>';

		return $div_start . $label . $input . $error_box . $div_end;
	}
}

if (!function_exists('form_dropdown_and_label'))
{
	function form_dropdown_and_label($name, $options, $selected = array(), $extra = '', $add_select = TRUE, $div = 'pure-control-group', $default_key = -1)
	{
		if ($add_select) $options = array($default_key => lang('select')) + $options;

		$div_start = empty($div) ? '' : '<div class="' . $div . '">';
		$label = form_label(lang($name), $name);
		$dropdown = form_dropdown($name, $options, set_value($name, $selected), 'id="' . $name . '"' . $extra);
		$error_box = form_error($name);
		$div_end = empty($div) ? '' : '</div>';
		
		return $div_start . $label . $dropdown . $error_box . $div_end;
	}
}

if (!function_exists('form_multiselect_and_label'))
{
	function form_multiselect_and_label($name, $options, $selected = array(), $extra = '', $div = 'pure-control-group')
	{
		$label_name = lang($name);
		$name .= '[]'; // CodeIgniter POST array syntax
		$extra .= ' class="chosen-select" data-placeholder="' . $label_name . '"';

		$div_start = empty($div) ? '' : '<div class="' . $div . '">';
		$label = form_label($label_name, $name);
		$dropdown = form_multiselect($name, $options, set_value($name, $selected), 'id="' . $name . '"' . $extra);
		$error_box = form_error($name);
		$div_end = empty($div) ? '' : '</div>';
		
		return $div_start . $label . $dropdown . $error_box . $div_end;
	}
}


if (!function_exists('form_radio_and_label'))
{
	function form_radio_and_label($name, $value, $current = '', $label = '', $checked = FALSE)
	{
		if (isset($current)) $checked = $current === $value;
		$id = $name . '_' . $value;

		$label_start = '<label for=' . $id .' class="pure-radio">';
		$radio = form_radio($name, $value, set_radio($name, $value, $checked), 'id="' . $id . '"');
		$text = empty($label) ? lang($value) : $label;
		$label_end = '</label>';

		return $label_start . $radio . ' ' . $text . $label_end;
	}
}

if (!function_exists('form_checkbox_and_label'))
{
	function form_checkbox_and_label($name, $value, $checked = FALSE, $div = 'pure-control-group', $label = '')
	{
		$id = $name . '_' . $value;
		$text = empty($label) ? lang($name) : $label;
		$name = $name . '[]';

		$div_start = empty($div) ? '' : '<div class="' . $div . '">';
		$label_start = '<label for=' . $id .' class="pure-checkbox">';
		$input = form_checkbox($name, $value, set_checkbox($id, $value, $checked), 'id="' . $id . '"');
		$label_end = '</label>';
		$div_end = empty($div) ? '' : '</div>';

		return $div_start . $label_start . $text . $label_end . $input . ' ' . $div_end;
	}
}

if (!function_exists('form_single_checkbox_and_label'))
{
	function form_single_checkbox_and_label($name, $value, $checked = FALSE, $div = 'pure-control-group', $label = '')
	{
		$id = $name;
		$text = empty($label) ? lang($name) : $label;
		$name = $name . '[]';
		$checked = empty($value) ? $checked : $value;

		$div_start = empty($div) ? '' : '<div class="' . $div . '">';
		$label_start = '<label for=' . $id .' class="pure-checkbox">';
		$input = form_checkbox($name, $value, $checked, 'id="' . $id . '"');
		$label_end = '</label>';
		$div_end = empty($div) ? '' : '</div>';

		return $div_start . $label_start . $text . $label_end . $input . $div_end;
	}
}

if (!function_exists('form_controls'))
{
	function form_controls($cancel_link = '')
	{
		$div_start = '<div class="pure-controls">';
		$div_start .= "<script type=\"text/javascript\">function clear_chosen_select()
{
	$('.chosen-select option').prop('selected', false);
	$(\".chosen-select\").trigger(\"chosen:updated\");
}</script>";
		$submit = form_submit('submit', lang('submit'), 'class="pure-button pure-button-primary"');
		$reset = form_reset('reset', lang('reset'), 'class="pure-button pure-button-secondary" onClick="clear_chosen_select()"');
		$cancel = !empty($cancel_link) ? form_cancel($cancel_link) : '';
		$div_end = '</div>';

		return $div_start . $submit . $reset . $cancel . $div_end;
	}
}

if (!function_exists('form_submit_only'))
{
	function form_submit_only($extra = '')
	{
		return form_submit('submit', lang('submit'), 'class="pure-button pure-button-primary"' . $extra);
	}
}

if (!function_exists('form_cancel'))
{
	function form_cancel($link)
	{
		return anchor($link, lang('back'), 'class="pure-button pure-button-primary" style="background: rgb(202, 60, 60);"');
	}
}
