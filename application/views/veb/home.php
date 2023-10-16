<?php if ($valid_token) { ?>
	<?=heading('Hartelijk dank voor het invullen van deze vragenlijst.', 2); ?>
	<p>
		Hartelijk dank voor het invullen van de vragenlijst <em><?=$test_name; ?></em>. 
		Uw antwoorden zijn opgeslagen en worden met de grootste zorg behandeld.
	</p>
	<p>
		Als u verder nog vragen heeft over de vragenlijst, neem dan contact op met het Babylab voor Taalonderzoek via <?=mailto(BABYLAB_MANAGER_EMAIL); ?>.
	</p>
	<p>
		U kunt het scherm nu afsluiten of doorklikken naar onze website:
		<?=anchor('https://babylab.wp.hum.uu.nl', 'https://babylab.wp.hum.uu.nl'); ?>.
	</p>
<?php 
} 
else 
{ 
	show_404(); 
} 
?>
