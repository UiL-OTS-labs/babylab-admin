<!-- Form for adding comments -->
<?=form_open('comment/add_submit/' . $participant->id, array('class' => 'pure-form')); ?>
<?=form_fieldset(lang('add_comment')); ?>
<?=$this->session->flashdata('comment_message'); ?>
<?=form_input('comment', '', 'required placeholder="' . lang('comment') . '"'); ?>

<?=form_submit_only(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
