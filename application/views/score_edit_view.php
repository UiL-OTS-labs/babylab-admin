<script type="text/javascript" src="js/score.js"></script>
<script type="text/javascript" src="js/testcats_filter_score.js"></script>
<script type="text/javascript" src="js/testsurveys_filter.js"></script>
<script type="text/javascript" src="js/participants_filter.js"></script>

<h2><?=lang('scores'); ?></h2>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?php if ($new_score) {
	echo form_dropdown_and_label('test', test_options($tests), $test_id);
	if (!empty($testcat_id)) {
		echo form_dropdown_and_label('testcat', testcat_options($testcats), $testcat_id);
	}
	else {
		echo form_dropdown_and_label('testcat', array(), array(), 'disabled');
	}
	if (!empty($testsurvey_id)) {
		echo form_dropdown_and_label('testsurvey', testsurvey_options($testsurveys), $testsurvey_id);
	}
	else {
		echo form_dropdown_and_label('testsurvey', array(), array(), 'disabled');
	}
	echo form_input_and_label('participant');
	echo form_hidden('participant_id', $participant_id);
} else { 
	echo form_input_and_label('test', $test->name, 'readonly');
	echo form_input_and_label('testcat', testcat_code_name($testcat), 'readonly');
	echo form_input_and_label('participant', name($participant), 'readonly');
	echo form_input_and_label('testsurvey', testsurvey_when($testsurvey->whensent, $testsurvey->whennr), 'readonly');
} ?>
	
<?=form_input_and_label('score', $score, 'class="positive-integer"'); ?>
<?=form_input_and_label('date', $date, 'id="score_datepicker"'); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
