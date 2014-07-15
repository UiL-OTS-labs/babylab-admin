<script type="text/javascript" src="js/participants_filter.js"></script>

<?=heading(lang('testinvites'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?php 
	echo form_dropdown_and_label('testsurvey', testsurvey_options($testsurveys), $testsurvey_id);
	echo form_input_and_label('participant');
	echo form_hidden('participant_id', $participant_id);
?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
