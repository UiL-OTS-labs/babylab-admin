<?=$this->session->flashdata('message'); ?>

<?=form_open('selfservice/submit/' . $current_language, array('class' => 'pure-form')); ?>
<?=form_fieldset($page_title); ?>

<p>Vul a.u.b. uw e-mailadres in waarbij u bij het Babylab Utrecht geregistreerd staat.</p>

<?=form_input('email', '', 'placeholder = "' . lang('email') . '"'); ?>

<?=form_submit_only(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>