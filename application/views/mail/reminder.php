<html>
<body>
<p>Beste <?=$name_parent; ?>,</p>
<p>Enige tijd geleden heeft u een afspraak gemaakt om met <?=$name_first; ?> naar het BabyLab te komen voor een experiment.
Wij willen u eraan herinneren dat dit experiment morgen plaatsvindt: <strong><?=$appointment; ?></strong> op het <strong>Janskerkof 13</strong>.</p>
<p>Informatie over het onderzoek kunt u terugvinden in de e-mail die wij ter bevestiging hebben gestuurd na het maken van de afspraak.</p>
<p>Als u bij de bevestiging ook een vragenlijst heeft ontvangen, kunt u dan zorgen dat u deze ingevuld heeft voor uw bezoek? Alvast bedankt!</p>
<p>Wanneer u verhinderd bent te komen of de afspraak graag wil verzetten, dan kunt u mailen naar: <?=mailto(BABYLAB_MANAGER_EMAIL); ?>. 
Mocht u verder nog vragen hebben dan kunt u contact opnemen met <?=BABYLAB_MANAGER; ?>: <?=BABYLAB_MANAGER_PHONE; ?>.</p>
<p>Meer informatie over het BabyLab en bijvoorbeeld hoe er te komen is beschikbaar op <a href="http://babylab.wp.hum.uu.nl">http://babylab.wp.hum.uu.nl</a>.</p>
<p>Wij zien ernaar uit u en <?=$name_first; ?> te zien op het BabyLab!</p>
<p>Tot morgen,</p>
<p><?=BABYLAB_MANAGER; ?> (lab manager)</p>
<p><em>Deze e-mail is automatisch gegenereerd.</em></p>
</body>
</html>