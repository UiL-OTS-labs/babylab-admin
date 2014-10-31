<html>
<body>
<p>Beste <?=$name_parent; ?>,</p>
<p>Graag willen wij u eraan herinneren dat wij u onlangs een e-mail hebben gestuurd met het verzoek een online vragenlijst in te vullen. 
Wij stuurden u deze e-mail, omdat <?=$name; ?> de leeftijd heeft bereikt voor het meetmoment van <?=$gender_pos; ?> taalvaardigheid na <?=$whennr; ?> maanden.</p>
<p>Deze vragenlijst is onderdeel van het experiment waarvoor u onlangs met <?=$name_first; ?> bij het Babylab bent/gaat langsgekomen.
Zo kunnen we <?=$gender_pos; ?> taalvaardigheid in verband brengen met de resultaten van het experiment. 
Wij zouden u dan ook willen vragen of u de link naar de vragenlijst aan het einde van deze e-mail wilt openen.</p>
<p>Het invullen duurt enige tijd. 
Daar wijzen wij u op, zodat u een goed moment kunt kiezen om de vragen te beantwoorden. 
Antwoorden kunnen wel tussentijds worden opgeslagen.</p>
<p>Belangrijk voor u om te weten, is dat u na afloop van de vragenlijst direct de resultaten te zien krijgt. 
U krijgt een curve te zien waarin de resultaten van <?=$name_first; ?> in vergelijking worden gebracht met resultaten van andere <?=$gender_plural; ?> op dezelfde leeftijd. 
Zo ziet u waar op dit moment de taalvaardigheid van <?=$name_first; ?> valt ten opzichte van het gemiddelde.</p>
<p>Wij willen u met nadruk laten weten dat wij zeer vertrouwelijk omgaan met de informatie die u ons toevertrouwt. 
Bovendien wordt de lijst op een veilige manier bewaard: de informatie is alleen toegankelijk voor de desbetreffende onderzoeker.</p>
<p>De link naar de vragenlijst (<em><?=$survey_name; ?></em>) vindt u hier: <?=$survey_link; ?>.</p>
<p>Mocht u vragen hebben n.a.v. de vragenlijst of de interpretatie daarvan, dan kunt u contact opnemen met <?=BABYLAB_MANAGER; ?>: <?=BABYLAB_MANAGER_PHONE; ?> of mailen naar: <?=mailto(BABYLAB_MANAGER_EMAIL); ?>.</p>
<p>Wij danken u alvast hartelijk voor uw medewerking, zonder uw deelname kunnen wij geen onderzoek doen!</p>
<p>Hartelijke groet,</p>
<p><?=BABYLAB_MANAGER; ?> (lab manager)</p>
<p><em>Deze e-mail is automatisch gegenereerd.</em></p>
</body>
</html>
