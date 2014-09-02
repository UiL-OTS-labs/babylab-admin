<link rel="stylesheet" href="css/chosen.css" />
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript">
$(function() {
	$(".chosen-select").chosen();
});
</script>
<?=heading(lang('testinvites'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?=form_dropdown_and_label('testsurvey', $testsurveys, array(), 'class="chosen-select" style="width: 350px;"', true, 'pure-control-group', -1);?>
<?=form_dropdown_and_label('participant', $participants, array(), 'class="chosen-select" style="width: 350px;"', true, 'pure-control-group', -1);?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
