<html>
<body>
<p>Beste <?=$name_parent; ?>,</p>
<p>Enige tijd geleden heeft u een afspraak gemaakt om met <?=$name_first; ?> naar het Babylab te komen voor een experiment.
Wij willen u eraan herinneren dat dit experiment morgen plaatsvindt: <strong><?=$appointment; ?></strong> op het <strong>Janskerkhof 13a</strong>.</p>
<p>Informatie over het onderzoek kunt u terugvinden in de e-mail die wij ter bevestiging hebben gestuurd na het maken van de afspraak.</p>
<p>Als u bij de bevestiging ook een vragenlijst heeft ontvangen, kunt u dan zorgen dat u deze ingevuld heeft voor uw bezoek? Alvast bedankt!</p>
<p>Als uw kind op dit moment oorontsteking heeft en dus mogelijk minder goed hoort, dan willen wij (indien mogelijk) de afspraak graag verzetten. Wanneer u verhinderd bent te komen of de afspraak graag wil verzetten, dan kunt u mailen naar: <?=mailto(BABYLAB_MANAGER_EMAIL); ?>.
Mocht u verder nog vragen hebben dan kunt u contact opnemen met:</p>
<?=ul($caller_contacts); ?>
<p>Meer informatie over het Babylab en bijvoorbeeld hoe er te komen is beschikbaar op <a href="https://babylab.wp.hum.uu.nl">https://babylab.wp.hum.uu.nl</a>.</p>
<p>Wij zien ernaar uit u en <?=$name_first; ?> te zien op het Babylab!</p>
<p>Tot morgen,</p>
<p><?=BABYLAB_TEAM; ?></p>
<p><em>Deze e-mail is automatisch gegenereerd.</em></p>
</body>
</html>
