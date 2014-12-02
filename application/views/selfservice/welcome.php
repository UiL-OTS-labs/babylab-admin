<?=heading('Selfservice Babylab Utrecht', 2); ?>

<?=$this->session->flashdata('message'); ?>

<p>Op deze pagina kunt u uw gegevens wijzigen en (indien gewenst) uw kinderen uitschrijven voor onderzoek van het BabyLab Utrecht en andere Babylabs.</p>

<?=form_open('submit', array('class' => 'pure-form pure-form-aligned')); ?>

<?=form_fieldset('Uw gegevens'); ?>
<?=form_input_and_label('parentfirstname', $parentfirstname, 'required'); ?>
<?=form_input_and_label('parentlastname', $parentlastname, 'required'); ?>
<?=form_input_and_label('city', $city); ?>
<?=form_input_and_label('phone', $phone, 'required'); ?>
<?=form_input_and_label('phonealt', $phonealt); ?>
<?=form_input_and_label('email', $email, 'required email="true"'); ?>

<?=form_fieldset_close(); ?>
<?=form_fieldset('Deelnemende kinderen'); ?>

<?php
    $tmpl = array('table_open' => '<table class="pure-table">' );

    $this->table->set_template($tmpl);
    $this->table->set_heading('Kind', lang('gender'), lang('dob'), 'Babylab Utrecht', 'Andere Babylabs');
    foreach ($participants as $p)
    {
        $this->table->add_row(name($p), gender_sex($p->gender), output_date($p->dateofbirth), form_checkbox('active'), form_checkbox('other'));
    } 
    echo $this->table->generate();
?>

<div class="pure-controls">
<?=form_submit('submit', 'Wijzigingen opslaan', 'class="pure-button pure-button-primary"'); ?>
</div>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>

<p>Als u andere opmerkingen of wijzigingen door wilt geven, kunt u ook e-mailen naar <?=mailto(BABYLAB_MANAGER_EMAIL); ?>.</p>
