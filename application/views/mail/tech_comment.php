<html>
<body>
<p>Dear lab staff,</p>
<p>A technical report was just filed by <em><?=$user_username; ?></em> at <a href="https://babylab-admin.hum.uu.nl">the Babylab admin interface</a>. 
The following problem was reported:</p>
<p><em><?=$message; ?></em></p>
<p>Details of the participation:</p>
<ul>
<li>Experiment: <?=$exp_name; ?></li> 
<li>Location: <?=$location; ?></li> 
<li>Date and time: <?=$appointment; ?></li> 
</ul>
<p>Please check and get back with the reporter (e-mail: <?=mailto($user_email); ?>) in due time. Thanks in advance!</p>
<p><em>This e-mail has been generated automatically.</em></p>
</body>
</html>
