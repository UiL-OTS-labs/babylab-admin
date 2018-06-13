<?=$this->session->flashdata('message'); ?>

<?php
// We do this check in the view, as flash-message can mess with navigating to other pages.
if (null !== $this->session->tempdata('login_attempts') && $this->session->tempdata('login_attempts') > 4) {
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
