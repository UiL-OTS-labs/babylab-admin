<?=heading(lang('participations'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form  pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?=form_dropdown_and_label('leader', $leaders, $leader, 'class="chosen-select"'); ?>

<?=form_controls('participation/get/' . $participation->id); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
