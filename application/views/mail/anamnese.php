<html>
<body>
<p>Beste <?=$name_parent; ?>,</p>
<?php if ($auto) { ?>
<p>Enige tijd geleden heeft u met uw <?=$gender; ?> <?=$name; ?> deelgenomen aan onderzoek aan het BabyLab van de Universiteit Utrecht. 
<?php } else { ?>
<p>Binnenkort gaat u met uw <?=$gender; ?> <?=$name; ?> deelnemen aan onderzoek aan het BabyLab van de Universiteit Utrecht. 
<?php } ?>
Hier zijn wij heel blij mee, zo kunnen vragen op het gebied van taalverwerving bij kinderen beantwoord worden.</p> 
<p>Een deel van het onderzoek bestaat uit het in verband brengen van de taak waarvoor u met <?=$name_first; ?> bent gekomen en <?=$gender_pos; ?> ontwikkeling op verschillende momenten.</p>
<p>Op dit moment heeft <?=$name_first; ?> de leeftijd bereikt voor het volgende meetmoment. 
Wij zouden u dan ook willen verzoeken om de link naar de vragen aan het einde van deze e-mail te openen. 
U krijgt een korte vragenlijst te zien; het invullen kost u 5 tot 10 minuten.</p>
<p>Wij willen u met nadruk laten weten dat wij zeer vertrouwelijk omgaan met de informatie die u ons toevertrouwt. 
Bovendien worden de antwoorden op een veilige manier bewaard: de informatie is alleen toegankelijk voor de desbetreffende onderzoeker.</p>
<p>De link naar de vragenlijst (<em><?=$survey_name; ?></em>) vindt u hier: <?=$survey_link; ?>.</p>
<p>Mocht u vragen hebben n.a.v. deze e-mail of de vragenlijst, dan kunt u contact met ons opnemen door te mailen naar <?=mailto(BABYLAB_MANAGER_EMAIL); ?>.</p>
<p>Wij danken u alvast hartelijk voor uw medewerking, zonder uw deelname kunnen wij geen onderzoek doen!</p>
<p>Hartelijke groet,</p>
<p><?=BABYLAB_TEAM; ?></p>
<p><em>Deze e-mail is automatisch gegenereerd.</em></p>
</body>
</html>
