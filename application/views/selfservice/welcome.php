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

<?=form_fieldset(lang('selfservice_form_heading')); ?>
<?=form_input_and_label('parentfirstname', $parentfirstname, 'required'); ?>
<?=form_input_and_label('parentlastname', $parentlastname, 'required'); ?>
<?=form_input_and_label('city', $city); ?>
<?=form_input_and_label('phone', $phone, 'required'); ?>
<?=form_input_and_label('phonealt', $phonealt); ?>
<?=form_input_and_label('email', $email, 'required email="true"'); ?>

<?=form_fieldset_close(); ?>
<?=form_fieldset(lang('selfservice_pps_heading')); ?>

<?php
    $tmpl = array('table_open' => '<table class="pure-table">' );

    $this->table->set_template($tmpl);
    $this->table->set_heading(ucfirst(lang('child')), lang('gender'), lang('dob'), 'Babylab Utrecht', lang('other_babylabs'));
    foreach ($participants as $p)
    {
        $this->table->add_row(name($p), gender_sex($p->gender), output_date($p->dateofbirth), 
            form_checkbox('active_' . $p->id, TRUE, $p->activated), 
            form_checkbox('other_' . $p->id, TRUE, $p->otherbabylabs));
    } 
    echo $this->table->generate();
?>

<div class="pure-controls">
<?=form_submit('submit', lang('save_changes'), 'class="pure-button pure-button-primary"'); ?>
</div>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>

<div id="actions">
    <?php
    if (isset($action_urls)) 
    {
        echo heading(lang('actions'), 3);
        $actions = array();
        foreach ($action_urls as $action_url) 
        {
            array_push($actions, anchor($action_url['url'], $action_url['title'], array('title' => $action_url['title'])));
        }
        echo ul($actions);
    }
    ?>
</div>
<p><?=sprintf(lang('selfservice_mail_comments_to'), mailto(BABYLAB_MANAGER_EMAIL));?>
