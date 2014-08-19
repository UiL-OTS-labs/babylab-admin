<?=heading(lang('comments'), 2); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?=form_textarea_and_label('comment', $comment, NULL, 'required'); ?>
<?=form_hidden('referrer', $referrer); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>