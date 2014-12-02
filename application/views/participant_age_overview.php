<?=form_open('participant/age_overview/', array('class' => 'pure-form')); ?>
<?=form_label('Aantal maanden vooruit kijken'); ?>
<?=form_input('months_from_now', $months_from_now, 'required class="positive-integer"'); ?>
<?=form_submit_only(); ?>
<?=form_close(); ?>
