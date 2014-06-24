<script type="text/javascript" src="js/date_range.js"></script>

<?php if (!isset($id)) { 
	echo heading($page_title, 2);
} ?>

<!-- Form for adding impediments -->
<?=form_open('impediment/add_submit/' . (isset($participant) ? $participant->id : ''), array('class' => 'pure-form')); ?>
<?=form_fieldset(lang('add_impediment')); ?>
<?=$this->session->flashdata('impediment_message'); ?>
<?=form_input('from', '', 'id="from" readonly placeholder="'. lang('from_date') . '"'); ?>
<?=form_input('to', '', 'id="to" readonly placeholder="'. lang('to_date') . '"'); ?>
<?=form_input('comment', '', 'placeholder="' . lang('comment') . '"'); ?>
<?php if (isset($participants)) { 
	echo br(2);
	echo form_label(lang('participant') . ': ', 'participant'); 
	$p_options = participant_options($participants);
	$p_options = array(lang('select')) + $p_options;
	echo form_dropdown('participant', $p_options);
} ?>

<?=form_submit_only(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
