<?=heading(lang('participations'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open('participation/cancel_submit/' . $participation->id, array('class' => 'pure-form')); ?>

<?=form_fieldset(lang('cancel')); ?>
<p>
    <?=sprintf(lang('cancel_info'), name($participant), $experiment->name, 
    anchor('participation/reschedule/' . $participation->id, lang('reschedule_short'))); ?> 
</p>

<p>
<?=form_checkbox('delete', 'delete'); ?>
<?=form_label(lang('delete')); ?>
<?=form_hidden('referrer', $referrer); ?>
</p>

<?=form_submit_only(); ?>
<?=form_cancel($referrer); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>