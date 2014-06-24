<?=heading(lang('participations'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open('participation/completed_submit/' . $participation->id, array('class' => 'pure-form')); ?>

<?=form_fieldset(lang('completed')); ?>
<?=form_input('comment', '', 'placeholder="' . lang('comment') . '"'); ?>

<!-- TODO: add result file here. -->

<?=form_submit_only(); ?>
<?=form_cancel('participation/experiment/' . $experiment->id); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
