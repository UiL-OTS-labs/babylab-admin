<?php

$lang['email_must_be_array'] = 'The e-mail validation method requires an array as argument.';
$lang['email_invalid_address'] = 'Invalid e-mail adress: %s';
$lang['email_attachment_missing'] = 'The following attachment could not be found: %s';
$lang['email_attachment_unreadable'] = 'Could not open this attachment: %s';
$lang['email_no_recipients'] = 'You need to specify the receivers in: To, Cc, or Bcc';
$lang['email_send_failure_phpmail'] = 'Could not sent e-mail using PHP mail(). Your server is probably not configured to use this method.';
$lang['email_send_failure_sendmail'] = 'Could not sent e-mail using PHP sendmail(). Your server is probably not configured to use this method.';
$lang['email_send_failure_smtp'] = 'Could not sent e-mail using PHP SMTP. Your server is probably not configured to use this method.';
$lang['email_sent'] = 'Your message has been succesfully sent using the following protecol: %s';
$lang['email_no_socket'] = 'Could not open a socket for Sendmail. Please verify your settings.';
$lang['email_no_hostname'] = 'You have not set an SMTP host name';
$lang['email_smtp_error'] = 'The following SMTP error occured: %s';
$lang['email_no_smtp_unpw'] = 'You need to provide an SMTP username and password.';
$lang['email_failed_smtp_login'] = 'The sending of AUTH LOGIN command failed. The following error was raised: %s';
$lang['email_smtp_auth_un'] = 'User name not found. The following error was raised: %s';
$lang['email_smtp_auth_pw'] = 'Password not found. The following error was raised: %s';
$lang['email_smtp_data_failure'] = 'Could not send data: %s';
$lang['email_exit_status'] = 'Exit status code: %s';
?>