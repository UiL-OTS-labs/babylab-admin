<script type="text/javascript" src="js/score.js"></script>
<script type="text/javascript" src="js/testcats_show_all.js"></script>

<h2><?=lang('scores'); ?></h2>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-stacked')); ?>
<?=form_fieldset($page_title); ?>

<div class="pure-g-r">
	<?=form_dropdown_and_label('participant', participant_options($participants), $participant_id, '', TRUE, 'pure-u-1-3'); ?>
	<?=form_dropdown_and_label('test', test_options($tests), $test_id, '', TRUE, 'pure-u-1-3'); ?>
	<?=form_input_and_label('date', $date, 'id="score_datepicker" required', FALSE, 'pure-u-1-3'); ?>
</div>

<?=form_fieldset_close(); ?>

<!-- Below, the scores will be shown on selection of the test or participant -->
<?=form_fieldset(lang('scores')); ?>
<div id="scores">
<?=lang('select_test_participant'); ?>
</div>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
