<?=doctype(); ?>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title><?=lang('error'); ?></title>
	<!-- CSS -->
	<?=link_tag('css/style.css'); ?>
</head>
<body>
	<div id="wrapper">
	<div class="failed"><?=$error; ?></div>
	</div>
</body>
</html>
