<!-- Form for editing the calendar comment on a participation -->
<?=heading(lang('participation'), 2); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?=form_textarea_and_label('calendar_comment', $calendar_comment, NULL, 'required'); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
