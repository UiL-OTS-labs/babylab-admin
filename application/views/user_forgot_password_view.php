<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form')); ?>
<?=form_fieldset($page_title); ?>

<p><?=lang('forgot_pw_instr'); ?></p>

<?=form_input('email', '', 'placeholder = "' . lang('email') . '"'); ?>

<?=form_submit_only(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
