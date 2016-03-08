<?=heading(lang('testinvites'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?=form_dropdown_and_label('testsurvey', $testsurveys, array(), 'class="chosen-select"'); ?>
<?=form_dropdown_and_label('participant', $participants, array(), 'class="chosen-select"'); ?>
<?=form_single_checkbox_and_label('concept', '1', FALSE, FALSE, sprintf(lang('concept_mail_only'), TO_EMAIL_OVERRIDE)); ?>
<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
