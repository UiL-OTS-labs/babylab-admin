<?=heading($page_title, 2); ?>

<?=form_open('user/reset_password_submit/' . $resetstring, array('class' => 'pure-form pure-form-aligned')); ?>

<?=form_input_and_label('password_new', '', 'required', TRUE); ?>
<?=form_input_and_label('password_conf', '', 'required', TRUE); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>