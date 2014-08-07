<?php if ($valid_token) { ?>
<?=heading('Hartelijk bedankt!', 2); ?>
<p>
	Hartelijk dank voor het invullen van de vragenlijst <em><?=$test_name; ?>
	</em>. Uw antwoorden zijn opgeslagen en worden met de grootste zorg
	behandeld.
</p>
<p>
	Als u verder nog vragen heeft over de vragenlijst, neem dan contact op
	met het Babylab Utrecht via
	<?=mailto('babylabutrecht@uu.nl'); ?>
	.
</p>
<p>
	U kunt het scherm nu afsluiten of doorklikken naar onze website:
	<?=anchor('http://babylab.wp.hum.uu.nl', 'http://babylab.wp.hum.uu.nl'); ?>
	.
</p>
	<?php } else { show_404(); } ?>