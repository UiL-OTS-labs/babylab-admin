<?=$this->session->flashdata('message'); ?>

<?php if (null !== $this->session->tempdata('login_attempts') && $this->session->tempdata('login_attempts') > 0) {
    echo lang('login_disabled');
} else {  ?>
    <?=form_open('login/submit/' . $language, array('class' => 'pure-form', 'autocomplete' => 'off')); ?>
    <?=form_fieldset(lang('login')); ?>

    <?=form_input('username', '', 'placeholder = "' . lang('username') . '"'); ?>
    <?=form_password('password', '', 'placeholder = "' . lang('password') . '"'); ?>

    <?=isset($referrer) ? form_hidden('referrer', $referrer) : ''; ?>

    <?=form_submit_only(); ?>
    <?=form_fieldset_close(); ?>
    <?=form_close(); ?>
    <?=validation_errors(); ?>
<?php } ?>
