<html>
<body>
<p>Beste <?=$name_parent; ?>,</p>
<p>Graag willen wij u eraan herinneren dat wij u onlangs een e-mail hebben gestuurd met het verzoek een anamnese in te vullen.</p>
<p>De link naar de vragenlijst (<em><?=$survey_name; ?></em>) vindt u hier: <?=$survey_link; ?>.</p>
<p>Mocht u vragen hebben n.a.v. de vragenlijst of de interpretatie daarvan, dan kunt u contact opnemen met <?=BABYLAB_MANAGER; ?>: <?=BABYLAB_MANAGER_PHONE; ?> of mailen naar: <?=mailto(BABYLAB_MANAGER_EMAIL); ?>.</p>
<p>Wij danken u alvast hartelijk voor uw medewerking, zonder uw deelname kunnen wij geen onderzoek doen!</p>
<p>Hartelijke groet,</p>
<p><?=BABYLAB_MANAGER; ?> (lab manager)</p>
<p><em>Deze e-mail is automatisch gegenereerd.</em></p>
</body>
</html>