<html>
<body>
<p><?=sprintf(lang('mail_heading'), $name_parent); ?></p>
<p><?=sprintf(lang('selfservice_mail_introduction'), anchor($url, 'deze link'));?></p>
<p><?=lang('selfservice_mail_link_failure');?><br/>
<?=site_url($url); ?></p>
<p><?=sprintf(lang('selfservice_mail_valid_one_day'), 
	anchor('selfservice', 'Babylab voor Taalonderzoek selfservice'), 
	BABYLAB_MANAGER, 
	mailto(BABYLAB_MANAGER_EMAIL));?>
</p>
<p><?=sprintf(lang('selfservice_mail_ending'), lang('babylab_team'));?></p>
<p><?=lang('mail_disclaimer');?></p>
