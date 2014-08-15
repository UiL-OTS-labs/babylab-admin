<?=doctype(); ?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=lang('error'); ?></title>
<!-- CSS -->
<?=link_tag('css/style.css'); ?>
</head>
<body>
	<div id="wrapper">
		<div class="failed">
		<?=$error; ?>
		</div>
	</div>
</body>
</html>
