<?=doctype(); ?>
<html lang="en">
<head>
<base href="<?=base_url(); ?>">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=lang('babylab'); ?></title>
<!-- Common JQuery -->
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<!-- (default) CSS -->
<?=link_tag('css/pure-min.css'); ?>
<?=link_tag('css/style.css'); ?>
<?=link_tag('css/menu.css'); ?>
<?=link_tag('css/chosen.css'); ?>
<!-- Date / Timepicker -->
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<?=link_tag('css/timepicker.css'); ?>
<?php if (current_language() === L::Dutch) { ?>
	<script type="text/javascript" src="js/jquery.ui.datepicker-nl.js"></script>
	<script type="text/javascript" src="js/jquery.ui.timepicker-nl.js"></script>
<?php } else { ?>
	<script>$(function() { $.datepicker.setDefaults({dateFormat : 'dd-mm-yy'}); });</script>
<?php } ?>
<!-- Validate -->
<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
<?php if (current_language() === L::Dutch) { ?>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/localization/messages_nl.js"></script>
<?php } ?>
<script type="text/javascript">$(function() {$('form').validate();});</script>
<!-- Numeric -->
<script type="text/javascript" src="js/jquery.numeric.js"></script>
<script type="text/javascript" src="js/jquery.numeric.addon.js"></script>
<!-- Sparkline -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
<script type="text/javascript">$(function() {$('.boxplot').sparkline('html', {type: 'box'});});</script>
<!-- Masks -->
<script type="text/javascript" src="js/jquery.mask.min.js"></script>
<!-- Google JS API -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<!-- Chosen -->
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript">
$(function() {
	$(".chosen-select").chosen({width: "350px"});
});
</script>

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

<div class="pure-g">
	<div class="pure-u-1-8"></div>
	<div id="wrapper" class="pure-u-3-4">

		<img id="header-img" src="images/uu-header.png">
		<?=heading(lang('babylab'), 1); ?>

		<?php if (is_admin()) { ?>
		<div id='cssmenu'>
			<ul>
				<li><?=anchor('welcome', lang('home')); ?></li>
				<li class='has-sub'><a href="javascript:void(0)"><?=lang('experiments'); ?></a>
					<ul>
						<li><?=anchor('experiment', lang('overview')); ?></li>
						<li><?=anchor('relation', lang('relations')); ?></li>
						<li class='last'><?=anchor('location', lang('locations')); ?></li>
					</ul>
				</li>
				<li class='has-sub'><a href="javascript:void(0)"><?=lang('participations'); ?></a>
					<ul>
						<li><?=anchor('participation', lang('overview')); ?></li>
						<li><?=anchor('call', lang('calls')); ?></li>
						<li><?=anchor('result', lang('results')); ?></li>
						<li><?=anchor('participation/no_shows', lang('no_shows')); ?></li>
						<li class='last'><?=anchor('participation/interruptions', lang('interruptions')); ?>
						</li>
					</ul>
				</li>
				<li class='has-sub'><a href="javascript:void(0)"><?=lang('calendar'); ?></a>
					<ul>
						<li><?=anchor('appointment', lang('calendar')); ?></li>
						<li><?=anchor('availability', lang('availability')); ?></li>
						<li class='last'><?=anchor('closing', lang('closings')); ?></li>
					</ul>
				</li>
				<li class='has-sub'><a href="javascript:void(0)"><?=lang('participants'); ?></a>
					<ul>
						<li><?=anchor('participant', lang('overview')); ?></li>
						<li><?=anchor('language', lang('languages')); ?></li>
						<li><?=anchor('dyslexia', lang('dyslexia')); ?></li>
						<li><?=anchor('impediment', lang('impediments')); ?></li>
						<li class='last'><?=anchor('comment', lang('comments')); ?></li>
					</ul>
				</li>
				<li class='has-sub'><a href="javascript:void(0)"><?=lang('users'); ?></a>
					<ul>
						<li><?=anchor('user', lang('overview')); ?></li>
						<li><?=anchor('caller', lang('callers')); ?></li>
						<li class='last'><?=anchor('leader', lang('leaders')); ?></li>
					</ul>
				</li>
				<li class='has-sub'><a href="javascript:void(0)"><?=lang('tests'); ?></a>
					<ul>
						<li><?=anchor('test', lang('overview')); ?></li>
						<li><?=anchor('testcat', lang('testcats')); ?></li>
						<li><?=anchor('percentile', lang('percentiles')); ?></li>
						<li class='last'><?=anchor('score', lang('scores')); ?></li>
					</ul>
				</li>
				<li class='has-sub last'><a href="javascript:void(0)"><?=lang('testsurveys'); ?></a>
					<ul>
						<li><?=anchor('testsurvey', lang('overview')); ?></li>
						<li class='last'><?=anchor('testinvite', lang('testinvites')); ?>
						</li>
					</ul>
				</li>
			</ul>
		</div>
		<?php } ?>

		<?php if (current_role() === UserRole::Caller) { ?>
		
		<div
			class="pure-menu pure-menu-open pure-menu-horizontal pure-menu-custom">
			<ul>
				<li><?=anchor('welcome', lang('home')); ?></li>
				<li><?=anchor('participation', lang('participations')); ?></li>
				<li><?=anchor('appointment', lang('calendar')); ?></li>
				<li><?=anchor('call/user/' . current_user_id(), lang('calls')); ?></li>
				<li><?=anchor('participant', lang('participants')); ?></li>
				<li><?=anchor('testinvite', lang('testinvites')); ?></li>
			</ul>
		</div>
		<?php } ?>

		<?php if (current_role() === UserRole::Leader) { ?>
		<div
			class="pure-menu pure-menu-open pure-menu-horizontal pure-menu-custom">
			<ul>
				<li><?=anchor('welcome', lang('home')); ?></li>
				<li><?=anchor('participation', lang('participations')); ?></li>
				<li><?=anchor('appointment', lang('calendar')); ?></li>
				<li><?=anchor('availability', lang('availability')); ?></li>
			</ul>
		</div>
		<?php } ?>

		<?php if (current_user_id() > 0) { ?>
		<div id="welcome">
		<?php 
			echo "<em>" . current_username() . " (" . strtolower(lang(current_role())) . ")" . "</em>";
			echo " | ";
			echo anchor('user/edit/' . current_user_id(), lang('edit_user_profile'));
			echo " | ";
			echo anchor('user/change_password/' . current_user_id(), lang('change_password'));
			echo " | ";
			if (user_role() === UserRole::Admin && !is_admin()) 
			{
				echo anchor('login/switch_to/admin', lang('login_admin'));
				echo " | ";
			}
			if ((user_role() === UserRole::Admin || user_role() === UserRole::Leader) && !is_leader()) 
			{
				echo anchor('login/switch_to/leader', lang('login_leader'));
				echo " | ";
			}
			if ((user_role() === UserRole::Admin || user_role() === UserRole::Leader) && !is_caller())
			{
				echo anchor('login/switch_to/caller', lang('login_caller'));
				echo " | ";
			}
			echo anchor('login/logout', lang('logout'));
		?>
		</div>
		<?php } ?>

		<?php if (current_user_id() == 0) { ?>
		<div
			class="pure-menu pure-menu-open pure-menu-horizontal pure-menu-custom">
			<a href="" class="pure-menu-heading"><?=lang('babylab'); ?> </a>
			<?php
			$english = !isset($language) || $language === L::English;
			$menu = array(
			anchor($english ? 'login' : 'inloggen', lang('login')),
			anchor($english ? 'forgot_password' : 'wachtwoord_vergeten', lang('forgot_password')),
			anchor($english ? 'register' : 'registreren', lang('reg_user')),
			anchor($english ? 'signup' : 'aanmelden', lang('reg_pp')),
			anchor($english ? 'selfservice' : 'selfservice', lang('selfservice')),
			 	anchor($english ? 'inloggen' : 'login', '<em>' . ($english ? 'Nederlands' : 'English') . '</em>')
			 	);
			 echo ul($menu);
		?>
		</div>
		<?php } ?>