<html>
<body>
<p>Beste <?=$name_parent; ?>,</p>
<?php if ($auto) { ?>
<p>Enige tijd geleden heeft u met uw <?=$gender; ?> <?=$name; ?> deelgenomen aan onderzoek aan het BabyLab van de Universiteit Utrecht. 
<?php } else { ?>
<p>Binnenkort gaat u met uw <?=$gender; ?> <?=$name_first; ?> deelnemen aan onderzoek aan het BabyLab van de Universiteit Utrecht. 
<?php } ?>
Hier zijn we heel blij mee, zo kunnen vragen op het gebied van taalverwerving bij kinderen beantwoord worden.</p>
<p>Een deel van het onderzoek bestaat uit het in verband brengen van de taak waarvoor u met <?=$name_first; ?> bent gekomen en <?=$gender_pos; ?> taalvaardigheid op dit moment. 
Om deze reden sturen wij u deze e-mail. 
<?php if ($auto) { ?>
<?=$name_first; ?> heeft nu de leeftijd bereikt voor het meetmoment na <?=$whennr; ?> maanden. 
<?php } ?>
Wij zouden u dan ook willen vragen of u de link naar de vragenlijst aan het einde van deze e-mail wilt openen. 
Het invullen duurt enige tijd. 
Daar wijzen wij u op, zodat u een goed moment kunt kiezen om de vragen te beantwoorden, anderzijds kunnen antwoorden wel tussentijds worden opgeslagen.</p>
<p>Belangrijk voor u om te weten, is dat u na afloop van de vragenlijst direct de resultaten te zien krijgt. 
U krijgt een curve te zien waarin de resultaten van <?=$name_first; ?> in vergelijking worden gebracht met resultaten van andere <?=$gender_plural; ?> op dezelfde leeftijd. 
Zo ziet u waar op dit moment de taalvaardigheid van <?=$name_first; ?> valt ten opzichte van het gemiddelde.</p>
<p>Wij willen u met nadruk laten weten dat wij zeer vertrouwelijk omgaan met de informatie die u ons toevertrouwt. 
Bovendien wordt de lijst op een veilige manier bewaard: de informatie is alleen toegankelijk voor de desbetreffende onderzoeker.</p>
<p>De link naar de vragenlijst (<em><?=$survey_name; ?></em>) vindt u hier: <?=$survey_link; ?>.</p>
<p>Mocht u vragen hebben n.a.v. de vragenlijst of de interpretatie daarvan, dan kunt u contact met ons opnemen door te mailen naar <?=mailto(BABYLAB_MANAGER_EMAIL); ?>.</p>
<p>Wij danken u alvast hartelijk voor uw medewerking, zonder uw deelname kunnen wij geen onderzoek doen!</p>
<p>Hartelijke groet,</p>
<p><?=BABYLAB_TEAM; ?></p>
<p><em>Deze e-mail is automatisch gegenereerd.</em></p>
</body>
</html>
