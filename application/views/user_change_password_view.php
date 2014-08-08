<h2>
<?=lang('users'); ?>
</h2>

<?=form_open('user/change_password_submit/' . $user_id, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?=form_input_and_label('username', $username, 'readonly'); ?>
<?=form_input_and_label('password_prev', '', 'required', TRUE); ?>
<?=form_input_and_label('password_new', '', 'required', TRUE); ?>
<?=form_input_and_label('password_conf', '', 'required', TRUE); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=validation_errors(); ?>
