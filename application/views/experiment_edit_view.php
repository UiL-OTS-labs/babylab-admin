<?=heading(lang('experiments'), 2); ?>
<link rel="stylesheet" media="screen" type="text/css" href="http://belelros.github.io/jQuery-ColorPicker/css/colorpicker.css" />
<!-- Color Picker -->
<script src="js/colorPicker.min.js"></script>
<script type="text/javascript">
<!--
$(function() {
	$("#wbs_number").on('keyup', function(e)  {
		   $(this).val($(this).val().toUpperCase());
	});
	
	$('#wbs_number').mask(
		'AA.000000.0',
		{'translation': 
			{
				A: {pattern: /[A-Za-z]/}
			}
		}
	);

	var widt = false;
    $('#colorselector2').ColorPicker({
        color: '<?=$experiment_color;?>',
        onShow: function (colpkr) {
            $(colpkr).fadeIn(500);
            return false;
        },
        onHide: function (colpkr) {
            $(colpkr).fadeOut(500);
            return false;
        },
        onChange: function (hsb, hex, rgb) {
            $('#colorselector2 label').css('background', 'url("../images/select.png") repeat scroll center center #' + hex);
            $('#experiment_color').val('#' + hex);
        }
    });
    $('#colorselector2 label').css('cursor', 'pointer');
    $('#colorselector2 label').css('background', 'url("../images/select.png") repeat scroll center center <?=$experiment_color;?>');
    $('#experiment_color').val('<?=$experiment_color;?>');
});        
//-->
</script>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>

<?=form_fieldset($page_title); ?>
<?=form_input_and_label('name', $name, 'required'); ?>
<?=form_input_and_label('type', $type, 'required'); ?>
<?=form_input_and_label('description', $description, 'required'); ?>
<?=form_input_and_label('duration', $duration, 'required class="positive-integer"'); ?>
<?=form_dropdown_and_label('location', location_options($locations), $location_id); ?>
<?=form_input_and_label('wbs_number', $wbs_number, 'required'); ?>
<?=form_colorPicker('experiment_color', $experiment_color,'required');?>

<?=form_fieldset('Eisen deelnemers'); ?>

<?=form_single_checkbox_and_label('multilingual', $multilingual); ?>
<?=form_single_checkbox_and_label('dyslexic', $dyslexic); ?>
<?=form_input_and_label('agefrommonths', $agefrommonths, 'required class="positive-integer"'); ?>
<?=form_input_and_label('agefromdays', $agefromdays, 'required class="positive-integer"'); ?>
<?=form_input_and_label('agetomonths', $agetomonths, 'required class="positive-integer"'); ?>
<?=form_input_and_label('agetodays', $agetodays, 'required class="positive-integer"'); ?>

<?=form_fieldset(lang('callers')); ?>
<?php
foreach ($callers as $caller)
{
	echo '<div class="pure-control-group">';
	echo form_label($caller->username, 'caller[]', array('class' => 'pure-checkbox'));
	echo form_checkbox('caller[]', $caller->id, isset($current_caller_ids) ? in_array($caller->id, $current_caller_ids) : FALSE);
	echo '</div>';
}
?>
<?=form_fieldset(lang('leaders')); ?>
<div class="pure-control-group">
<?php
foreach ($leaders as $leader)
{
	echo '<div class="pure-control-group">';
	echo form_label($leader->username, 'leader[]', array('class' => 'pure-checkbox'));
	echo form_checkbox('leader[]', $leader->id, isset($current_leader_ids) ? in_array($leader->id, $current_leader_ids) : FALSE);
	echo '</div>';
}
?>
</div>
<?=form_fieldset(lang('prerequisite')); ?>
<div class="pure-control-group">
<?php
foreach ($prerequisites as $prerequisite)
{
	if ($prerequisite->id != $id)
	{
		echo '<div class="pure-control-group">';
		echo form_label($prerequisite->name, 'prerequisite[]', array('class' => 'pure-checkbox'));
		echo form_checkbox('prerequisite[]', $prerequisite->id, isset($current_prerequisite_ids) ? in_array($prerequisite->id, $current_prerequisite_ids) : FALSE);
		echo '</div>';
	}
}
?>
</div>
<?=form_fieldset(lang('excludes')); ?>
<div class="pure-control-group">
<?php
foreach ($excludes as $exclude)
{
	if ($exclude->id != $id)
	{
		echo '<div class="pure-control-group">';
		echo form_label($exclude->name, 'exclude[]', array('class' => 'pure-checkbox'));
		echo form_checkbox('exclude[]', $exclude->id, isset($current_exclude_ids) ? in_array($exclude->id, $current_exclude_ids) : FALSE);
		echo '</div>';
	}
}
?>
</div>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>

