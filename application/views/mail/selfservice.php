<html>
<body>
<p>Beste <?=$name_parent; ?>,</p>
<p>U heeft via de selfservice van het Babylab Utrecht een verzoek gedaan om uw gegevens te wijzigen.
U kunt in de selfservice-pagina uw contactgegevens aanpassen en u kunt uw deelnemende kinderen aan- en afmelden voor het Babylab Utrecht en andere Babylabs van de Universiteit Utrecht.</p>
<p>U kunt uw gegevens aanpassen via <?=anchor($url, 'deze link'); ?>.</p>
<p>Als deze link niet werkt, kopieer dan deze link naar uw browser: <?=$url; ?></p>
<p>Bovenstaande link is vanaf het moment van het verzenden van deze e-mail voor een dag geldig. 
Mocht de link verlopen zijn, dan kunt u opnieuw een verzoek tot aanpassen doen via de <?=anchor('selfservice', 'Babylab Utrecht selfservice'); ?>. 
Mocht u verder nog vragen of opmerkingen hebben, dan kunt u contact opnemen met <?=BABYLAB_MANAGER; ?>: <?=BABYLAB_MANAGER_PHONE; ?> of mailen naar: <?=mailto(BABYLAB_MANAGER_EMAIL); ?>.</p>
<p>Hartelijke groet,</p>
<p><?=BABYLAB_TEAM; ?></p>
<p><em>Deze e-mail is automatisch gegenereerd.</em></p>

