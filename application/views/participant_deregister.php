<script type="text/javascript" src="js/dob.js"></script>

<?=heading($page_title, 2); ?>

<p><?=lang('deregister_pageintro');?></p> 

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>

<?=form_fieldset(lang('data_child')); ?>
<?=form_input_and_label('firstname', $firstname, 'required'); ?>
<?=form_input_and_label('lastname', $lastname, 'required'); ?>
<?=form_input_and_label('dob', $dob, 'id="birth_datepicker" required'); ?>
<?=form_fieldset(lang('data_end')); ?>
<?=form_input_and_label('email', $email, 'required email="true"'); ?>
<?=form_textarea_and_label('reason'); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
