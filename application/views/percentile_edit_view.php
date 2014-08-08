<script type="text/javascript" src="js/testcats_filter.js"></script>

<?=heading(lang('percentiles'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?php
if ($new_percentile) {
	echo form_dropdown_and_label('test', test_options($tests), $test_id);
	if (!empty($testcat_id)) {
		echo form_dropdown_and_label('testcat', testcat_options($testcats), $testcat_id);
	}
	else {
		echo form_dropdown_and_label('testcat', array(), array(), 'disabled');
	}
}
else {
	echo form_input_and_label('test', $test->name, 'readonly');
	echo form_input_and_label('testcat', testcat_code_name($testcat), 'readonly');
}
?>
<div class="pure-control-group">
<?=form_label(lang('gender'), 'gender'); ?>
<?=form_radio_and_label('gender', Gender::Female, $gender, gender(Gender::Female)); ?>
<?=form_radio_and_label('gender', Gender::Male, $gender, gender(Gender::Male)); ?>
</div>
<?=form_input_and_label('age', $age, 'class="positive-integer"'); ?>
<?=form_input_and_label('score', $score, 'required="true" class="positive-integer"'); ?>
<?=form_input_and_label('percentile', $percentile, 'required="true" max="100" class="positive-integer"'); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=validation_errors(); ?>
