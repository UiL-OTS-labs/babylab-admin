<script type="text/javascript" src="js/score.js"></script>

<?=heading(lang('dyslexia'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?php if ($new_dyslexia) {
	echo form_dropdown_and_label('participant', participant_options($participants), $participant_id);
} else { 
	echo form_input_and_label('participant', name($participant), 'readonly');
} ?>

<div class="pure-control-group">
	<?=form_label(lang('parent'), 'gender'); ?>
	<?=form_radio_and_label('gender', Gender::Female, $gender, gender_parent(Gender::Female)); ?>
	<?=form_radio_and_label('gender', Gender::Male, $gender, gender_parent(Gender::Male)); ?>
</div>
<?=form_single_checkbox_and_label('statement', $statement); ?>
<?=form_input_and_label('emt_score', $emt_score, 'class="positive-integer"'); ?>
<?=form_input_and_label('klepel_score', $klepel_score, 'class="positive-integer"'); ?>
<?=form_input_and_label('vc_score', $vc_score, 'class="positive-integer"'); ?>
<?=form_input_and_label('date', $date, 'id="score_datepicker"'); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
