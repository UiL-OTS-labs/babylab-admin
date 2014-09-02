<?=heading(lang('participation'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<p>
<?=sprintf(lang('complete_part_info'), $participant_name, $experiment_name); ?>
</p>

<?=form_open('participation/completed_submit/' . $participation_id, array('class' => 'pure-form  pure-form-aligned')); ?>

<?=form_fieldset(lang('complete_part')); ?>
<?=form_input_and_label('part_number', $part_number, 'required'); ?>
<div class="pure-control-group">
<?=form_label(lang('interrupted'), 'gender'); ?>
<?=form_radio_and_label('interrupted', '1', $interrupted, lang('yes')); ?>
<?=form_radio_and_label('interrupted', '0', $interrupted, lang('no')); ?>
</div>
<p class="warning"><?=lang('part_comment_info'); ?></p>
<?=form_textarea_and_label('comment', $comment, lang('part_comment'), 'required'); ?>
<p class="warning"><?=lang('pp_comment_info'); ?></p>
<?=form_textarea_and_label('pp_comment', $pp_comment, lang('pp_comment')); ?>
<p class="warning"><?=lang('tech_comment_info'); ?></p>
<?=form_textarea_and_label('tech_comment', $tech_comment, lang('tech_comment')); ?>

<!-- TODO: add result file here. -->

<?=form_controls('participation/experiment/' . $experiment_id); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
