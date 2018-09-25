<script>
$(function() {
    $(":checkbox[name^='active']").change(function() {
        // If the 'active' checkbox is unchecked...
        if (!$(this).is(':checked')) {
            // ... select the next checkbox, set it to false
            $(this).parent().next().children().attr('checked', false);
        }
    });
});
</script>

<?=heading(lang('selfservice_welcome'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<p><?=lang('selfservice_explanation');?></p>

<?=form_open('selfservice/welcome_submit', array('class' => 'pure-form pure-form-aligned')); ?>

<?=form_fieldset(lang('selfservice_contact_heading')); ?>
<?=form_input_and_label('parentfirstname', $parentfirstname, 'required'); ?>
<?=form_input_and_label('parentlastname', $parentlastname, 'required'); ?>
<?=form_input_and_label('city', $city); ?>
<?=form_input_and_label('phone', $phone, 'required'); ?>
<?=form_input_and_label('phonealt', $phonealt); ?>
<?=form_input_and_label('email', $email, 'required email="true"'); ?>
<div class="pure-control-group">
    <?=form_label(lang('newsletter').'*', 'newsletter'); ?>
    <?=form_radio_and_label('newsletter', '1', $newsletter, lang('yes')); ?>
    <?=form_radio_and_label('newsletter', '0', $newsletter, lang('no'), TRUE); ?>
</div>
<p>* <?=lang('newsletter_q')?></p>

<?=form_fieldset_close(); ?>
<?=form_fieldset(lang('selfservice_pps_heading')); ?>
<p><?=sprintf(lang('selfservice_pps_help'), mailto(BABYLAB_MANAGER_EMAIL))?></p>

<?php
    $tmpl = array('table_open' => '<table class="pure-table">' );

    $this->table->set_template($tmpl);
    $this->table->set_heading(ucfirst(lang('child')), lang('gender'), lang('dob'), 'Babylab Utrecht');
    foreach ($participants as $p)
    {
        $this->table->add_row(name($p), gender_sex($p->gender), output_date($p->dateofbirth), 
            form_checkbox('active_' . $p->id, TRUE, $p->activated));
    }
    echo $this->table->generate();
?>

<div class="pure-controls">
<?=form_submit('submit', lang('save_changes'), 'class="pure-button pure-button-primary"'); ?> 
<?=form_submit('register', lang('selfservice_reg_pp'), 'class="pure-button pure-button-primary"'); ?>
</div>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>