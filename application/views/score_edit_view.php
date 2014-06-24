<script type="text/javascript" src="js/score.js"></script>
<script type="text/javascript" src="js/testcats_filter.js"></script>

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
	echo form_dropdown_and_label('participant', participant_options($participants), $participant_id);
} else { 
	echo form_input_and_label('test', $test->name, 'readonly');
	echo form_input_and_label('testcat', testcat_code_name($testcat), 'readonly');
	echo form_input_and_label('participant', name($participant), 'readonly');
} ?>
	
<?=form_input_and_label('score', $score, 'class="positive-integer"'); ?>
<?=form_input_and_label('date', $date, 'id="score_datepicker"'); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
