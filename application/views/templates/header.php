<?=doctype(); ?>
<html lang="en">
<head>
	<base href="<?=base_url(); ?>">
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title><?=lang('babylab'); ?></title>
<!-- Common JQuery -->
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<script type="text/javascript" src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<!-- (default) CSS -->
	<?=link_tag('css/pure-min.css'); ?>
	<?=link_tag('css/style.css'); ?>
	<?=link_tag('css/menu.css'); ?>
<!-- Date / Timepicker -->
	<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
	<?=link_tag('css/timepicker.css'); ?>
	<?php if (current_language() === L::Dutch) { ?>
		<script type="text/javascript" src="js/jquery.ui.datepicker-nl.js"></script>
		<script type="text/javascript" src="js/jquery.ui.timepicker-nl.js"></script>
	<?php } ?>
<!-- Validate -->
	<script type="text/javascript" src="js/jquery.validate.js"></script>
	<?php if (current_language() === L::Dutch) { ?>
		<script type="text/javascript" src="js/jquery.validate-nl.js"></script>
	<?php } ?>
	<script type="text/javascript">$(function() {$('form').validate();});</script>
<!-- Numeric -->
	<script type="text/javascript" src="js/jquery.numeric.js"></script>
	<script type="text/javascript" src="js/jquery.numeric.addon.js"></script>
<!-- Sparkline -->
	<script type="text/javascript" src="js/jquery.sparkline.js"></script>
	<script type="text/javascript">$(function() {$('.boxplot').sparkline('html', {type: 'box'});});</script>
<!-- Auto tabindex for inputs TODO: exclude hidden inputs -->
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
<h1><?=lang('babylab'); ?></h1>

<?php if (is_admin()) { ?>
<div id='cssmenu'>
	<ul>
		<li><?=anchor('welcome', lang('home')); ?></li>
		<li class='has-sub'><?=anchor('experiment', lang('experiments')); ?>
		<ul>
			<li><?=anchor('relation', lang('relations')); ?></li>
			<li class='last'><?=anchor('location', lang('locations')); ?></li>
		</ul>
		</li>
		<li class='has-sub'><?=anchor('participation', lang('participations')); ?>
		<ul>
			<li><?=anchor('call', lang('calls')); ?></li>
			<li><?=anchor('result', lang('results')); ?></li>
			<li><?=anchor('participation/no_shows', lang('no_shows')); ?></li>
			<li class='last'><?=anchor('participation/interruptions', lang('interruptions')); ?></li>
		</ul>
		</li>
		<li class='has-sub'><?=anchor('participant', lang('participants')); ?>
		<ul>
			<li><?=anchor('language', lang('languages')); ?></li>
			<li><?=anchor('dyslexia', lang('dyslexia')); ?></li>
			<li><?=anchor('impediment', lang('impediments')); ?></li>
			<li class='last'><?=anchor('comment', lang('comments')); ?></li>
		</ul>
		</li>
		<li class='has-sub'><?=anchor('user', lang('users')); ?>
		<ul>
			<li><?=anchor('caller', lang('callers')); ?></li>
			<li class='last'><?=anchor('leader', lang('leaders')); ?></li>
		</ul>
		</li>
		<li class='has-sub last'><?=anchor('test', lang('tests')); ?>
		<ul>
			<li><?=anchor('testcat', lang('testcats')); ?></li>
			<li><?=anchor('percentile', lang('percentiles')); ?></li>
			<li class='last'><?=anchor('score', lang('scores')); ?></li>
		</ul>
		</li>
		<li class='has-sub last'><?=anchor('testsurvey', lang('testsurveys')); ?>
		<ul>
			<li class='last'><?=anchor('testinvite', lang('testinvites')); ?></li>
		</ul>
		</li>
	</ul>
</div>
<?php } ?>

<?php if (current_role() === UserRole::Caller) { ?>
	<div class="pure-menu pure-menu-open pure-menu-horizontal pure-menu-custom">
	<ul>
		<li><?=anchor('welcome', lang('home')); ?></li>
		<li><?=anchor('participation', lang('participations')); ?></li>
		<li><?=anchor('call/user/' . current_user_id(), lang('calls')); ?></li>
		<li><?=anchor('participant', lang('participants')); ?></li>
		<li><?=anchor('testinvite', lang('testinvites')); ?></li>
	</ul>
	</div>
<?php } ?>

<?php if (current_role() === UserRole::Leader) { ?>
	<div class="pure-menu pure-menu-open pure-menu-horizontal pure-menu-custom">
	<ul>
		<li><?=anchor('welcome', lang('home')); ?></li>
		<li><?=anchor('experiment', lang('experiments')); ?></li>
		<li><?=anchor('participation', lang('participations')); ?></li>
		<li><?=anchor('participant', lang('participants')); ?></li>
	</ul>
	</div>
<?php } ?>

<?php if (current_user_id() > 0) { ?>
	<div id="welcome">
		<i><?=current_username(); ?></i> |
		<?=anchor('user/edit/' . current_user_id(), lang('edit_user_profile')); ?> |
		<?=anchor('user/change_password/' . current_user_id(), lang('change_password')); ?> |
		<?=anchor('login/logout', lang('logout')); ?>
	</div>
<?php } ?>

<?php if (current_user_id() == 0) { ?>
	<div class="pure-menu pure-menu-open pure-menu-horizontal pure-menu-custom">
		<a href="" class="pure-menu-heading"><?=lang('babylab'); ?></a>
		<?php
			 $english = !isset($language) || $language === L::English; 
			 $menu = array(
			 	anchor($english ? 'login' : 'inloggen', lang('login')),
			 	anchor($english ? 'forgot_password' : 'wachtwoord_vergeten', lang('forgot_password')),
			 	anchor($english ? 'register' : 'registreren', lang('reg_user')),
			 	anchor($english ? 'signup' : 'aanmelden', lang('reg_pp')),
			 	anchor($english ? 'inloggen' : 'login', '<em>' . ($english ? 'Nederlands' : 'English') . '</em>')
			 	);
			 echo ul($menu);
		?>
	</div>
<?php } ?>
