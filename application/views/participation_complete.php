<script type="text/javascript">
$(function() 
{
    $('input:radio[name="excluded"]').change(function() 
    {
        var excluded = $(this).val() == '1';
        $('select[name="excluded_reason"]').parent().toggle(excluded);
    });

    $('input:radio[name="excluded"]:checked').change();
});
</script>

<?=heading(lang('participation'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<p><?=sprintf(lang('complete_part_info'), $participant_name, $experiment_name); ?></p>

<?=form_open('participation/completed_submit/' . $participation_id, array('class' => 'pure-form  pure-form-aligned')); ?>

<?=form_fieldset(lang('complete_part')); ?>
<?=form_input_and_label('part_number', $part_number, 'required'); ?>
<div class="pure-control-group">
<?=form_label(lang('interrupted_long'), 'interrupted'); ?>
<?=form_radio_and_label('interrupted', '1', $interrupted, lang('yes')); ?>
<?=form_radio_and_label('interrupted', '0', $interrupted, lang('no'), TRUE); ?>
</div>
<div class="pure-control-group">
<?=form_label(lang('excluded_long'), 'excluded'); ?>
<?=form_radio_and_label('excluded', '1', $excluded, lang('yes')); ?>
<?=form_radio_and_label('excluded', '0', $excluded, lang('no'), TRUE); ?>
</div>
<?=form_dropdown_and_label('excluded_reason', array(
    ExcludedReason::CRYING                  => 'Huilen',
    ExcludedReason::FUSSY_OR_RESTLESS         => 'Onrustig gedrag', 
    ExcludedReason::PARENTAL_INTERFERENCE    => 'Inmenging ouder',
    ExcludedReason::TECHNICAL_PROBLEMS       => 'Technische problemen',
    ExcludedReason::INTERRUPTED             => lang('interrupted_long'),
    ExcludedReason::OTHER                   => 'Anders',
    ), $excluded_reason); ?>

<?=form_fieldset(lang('comments')); ?>
<p class="warning"><?=lang('part_comment_info'); ?></p>
<?=form_textarea_and_label('comment', $comment, lang('part_comment'), 'required'); ?>
<p class="warning"><?=lang('pp_comment_info'); ?></p>
<?=form_textarea_and_label('pp_comment', $pp_comment, lang('pp_comment')); ?>
<p class="warning"><?=lang('tech_comment_info'); ?></p>
<?=form_textarea_and_label('tech_comment', $tech_comment, lang('tech_comment')); ?>

<?=form_fieldset(lang('actions')); ?>
<?=form_single_checkbox_and_label('cancelled_complete', '1'); ?>

<!-- TODO: add result file here. -->

<?=form_controls('participation/experiment/' . $experiment_id); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
