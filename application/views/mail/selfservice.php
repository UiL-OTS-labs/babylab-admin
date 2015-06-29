<p><?=sprintf(lang('selfservice_mail_introduction'), anchor($url, 'deze link'));?></p>
<p><?=lang('selfservice_mail_link_failure');?><br/>
<?=site_url($url); ?></p>
<p><?=sprintf(lang('selfservice_mail_valid_one_day'), 
	anchor('selfservice', 'Babylab Utrecht selfservice'), 
	BABYLAB_MANAGER, 
	BABYLAB_MANAGER_PHONE, 
	mailto(BABYLAB_MANAGER_EMAIL));?>
</p>
