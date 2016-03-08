<?=heading(lang('dyslexia'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?php if ($new_dyslexia) {
	echo form_dropdown_and_label('participant', $participants, array(), 'class="chosen-select"');
} else {
	echo form_input_and_label('participant', name($participant), 'readonly');
} ?>

<div class="pure-control-group">
<?=form_label(lang('parent'), 'gender'); ?>
<?=form_radio_and_label('gender', Gender::Female, $gender, gender_parent(Gender::Female)); ?>
<?=form_radio_and_label('gender', Gender::Male, $gender, gender_parent(Gender::Male)); ?>
<?=form_error('gender'); ?>
</div>
<?=form_single_checkbox_and_label('statement', '1', $statement); ?>
<?=form_input_and_label('emt_score', $emt_score, 'class="positive-integer"'); ?>
<?=form_input_and_label('klepel_score', $klepel_score, 'class="positive-integer"'); ?>
<?=form_input_and_label('vc_score', $vc_score, 'class="positive-integer"'); ?>
<?=form_textarea_and_label('comment', $comment); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
