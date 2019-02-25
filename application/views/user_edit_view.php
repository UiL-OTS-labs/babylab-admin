<?=heading(lang('users'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>

<?=form_fieldset($page_title); ?>

<?=form_input_and_label('username', $username, !$new_user ? 'readonly' : 'required'); ?>
<?php if ($new_user) { ?>
    <?=form_input_and_label('password', '', 'required', TRUE); ?>
    <?=form_input_and_label('password_conf', '', 'required', TRUE); ?>
<?php } ?>
<?php if (is_admin()) { ?>
<div class="pure-control-group">
    <?=form_label(lang('role'), 'role'); ?>
    <?=form_radio_and_label('role', UserRole::ADMIN, $role); ?>
    <?=form_radio_and_label('role', UserRole::LEADER, $role); ?>
    <?=form_radio_and_label('role', UserRole::RESEARCHER, $role); ?>
    <?=form_radio_and_label('role', UserRole::CALLER, $role); ?>
</div>
<div class="pure-control-group">
	<?=form_label(lang('needssignature'), 'needssignature'); ?>
	<?=form_radio_and_label('needssignature', '1', $needssignature, lang('yes'), TRUE); ?>
	<?=form_radio_and_label('needssignature', '0', $needssignature, lang('no')); ?>
</div>
<?php } ?>
<?=form_input_and_label('firstname', $firstname, 'required'); ?>
<?=form_input_and_label('lastname', $lastname, 'required'); ?>
<?=form_input_and_label('email', $email, 'required'); ?>
<?=form_input_and_label('phone', $phone); ?>
<?=form_input_and_label('mobile', $mobile); ?>
<div class="pure-control-group">
    <?=form_label(lang('preferredlanguage'), 'preferredlanguage'); ?>
    <?=form_radio_and_label('preferredlanguage', 'en', $preferredlanguage, lang(L::ENGLISH)); ?>
    <?=form_radio_and_label('preferredlanguage', 'nl', $preferredlanguage, lang(L::DUTCH)); ?>
</div>
<?=form_hidden('referrer', $referrer); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
