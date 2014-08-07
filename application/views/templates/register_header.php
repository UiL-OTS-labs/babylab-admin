<?=doctype(); ?>
<html lang="en">
<head>
<base href="<?=base_url(); ?>">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title><?=lang('babylab'); ?></title>
<!-- Common JQuery -->
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<link rel="stylesheet"
	href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript"
	src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<!-- (default) CSS -->
<?=link_tag('css/pure-min.css'); ?>
<?=link_tag('css/style.css'); ?>
<!-- Date / Timepicker -->
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<?=link_tag('css/timepicker.css'); ?>
<?php if ($current_language === L::Dutch) { ?>
<script type="text/javascript" src="js/jquery.ui.datepicker-nl.js"></script>
<script type="text/javascript" src="js/jquery.ui.timepicker-nl.js"></script>
<?php } ?>
<!-- Validate -->
<script type="text/javascript" src="js/jquery.validate.js"></script>
<?php if ($current_language === L::Dutch) { ?>
<script type="text/javascript" src="js/jquery.validate-nl.js"></script>
<?php } ?>
<script type="text/javascript">$(function() {$('form').validate();});</script>
<!-- Numeric -->
<script type="text/javascript" src="js/jquery.numeric.js"></script>
<script type="text/javascript" src="js/jquery.numeric.addon.js"></script>
<!-- Auto tabindex for inputs -->
<script>
	$(function() {
		$('select, input').each(function(index) {
		    $(this).attr('tabindex', index + 1)
		});
	});
	</script>
</head>
<body>

	<div id="wrapper">

		<img id="header-img" src="images/uu-header.png">
		<h1>
		<?=lang('babylab'); ?>
		</h1>
		<hr>