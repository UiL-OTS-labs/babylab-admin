<?=doctype(); ?>
<html lang="en">
<head>
	<base href="<?=base_url(); ?>">
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title><?=lang('babylab'); ?></title>
<!-- Common JQuery -->
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<!-- Google JS API -->
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<!-- (default) CSS -->
	<?=link_tag('css/pure-min.css'); ?>
	<?=link_tag('css/style.css'); ?>
</head>
<body>

<div id="wrapper">

<img id="header-img" src="images/uu-header.png">
<h1><?=lang('babylab'); ?></h1>

<div class="pure-menu pure-menu-open pure-menu-horizontal pure-menu-custom">
<?php
	 if ($valid_token) 
	 {
	 	$menu = array(
		 	anchor('c/' . $test_code . '/' . $token . '/home/', lang('home')),
		 	anchor('c/' . $test_code . '/' . $token . '/sc', lang('scores')),
		 	anchor('c/' . $test_code . '/' . $token . '/cp', lang('percentiles')),
		 	anchor('c/' . $test_code . '/' . $token . '/vs', 'Alle scores'),
		 	anchor('c/' . $test_code . '/' . $token . '/more', 'Meer informatie')
		 	);
	 }
	 else 
	 {
	 	$menu = array(
		 	anchor('c/' . $test_code, lang('home')),
		 	anchor('c/' . $test_code . '/cp', lang('percentiles')),
		 	anchor('c/' . $test_code . '/vs', 'Alle scores'),
		 	anchor('c/' . $test_code . '/more', 'Meer informatie')
		 	);
	 }
	 echo ul($menu);
?>
</div>
