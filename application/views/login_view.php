<?=$this->session->flashdata('message'); ?>

<?=form_open('login/submit/' . $language, array('class' => 'pure-form')); ?>
<?=form_fieldset(lang('login')); ?>

<?=form_input('username', '', 'placeholder = "' . lang('username') . '"'); ?>
<?=form_password('password', '', 'placeholder = "' . lang('password') . '"'); ?>

<?=form_submit_only(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
