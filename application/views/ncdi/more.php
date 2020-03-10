<?=heading($page_title, 2); ?>

<p>Meer informatie over deze test vindt u op <?=anchor('http://www.taalexpert.nl/test.aspx?id=173', 'taalexpert.nl', 'target="_blank"'); ?>.
</p>
<p>Bij vragen of opmerkingen over de resultaten op deze site kunt u
contact opnemen met <?=BABYLAB_MANAGER; ?> (<?=mailto(BABYLAB_MANAGER_EMAIL); ?>).
</p>

<?php if (!$valid_token) { ?>
<p>Ook meedoen aan deze test en andere experimenten? Meld uw kind dan aan voor het Babylab Utrecht.<br>
Lees daar <?=anchor('https://babylab.wp.hum.uu.nl/meedoen', 'hier'); ?> meer over en klik hier voor het <?=anchor('/aanmelden/', 'aanmeldingsformulier'); ?>.
</p>
<?php } ?>

<?=heading('Colofon', 3); ?>
<p><em>Deze pagina's zijn samengesteld door het <?=anchor('https://babylab.wp.hum.uu.nl', lang('babylab')); ?> 
(e-mail: <?=mailto(BABYLAB_MANAGER_EMAIL); ?>).</em></p>
