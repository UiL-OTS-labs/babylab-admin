<?=heading(lang('results'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?=form_input_and_label('phasenr', $phasenr, 'required'); ?>
<?=form_input_and_label('phase', $phase, 'required'); ?>
<?=form_input_and_label('trial', $trial, 'required'); ?>
<?=form_input_and_label('lookingtime', $lookingtime, 'required'); ?>
<?=form_input_and_label('nrlooks', $nrlooks, 'required'); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
