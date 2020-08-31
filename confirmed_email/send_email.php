<?php
require_once "../vendor/autoload.php";
require_once "../model/db.php";
function confirmed_email($email, $password, $login = "")
{
	// генерация случайной строки для отсылки на почту в качестве индефикатора потдвирждения
	$code = md5($password . $email);
	$message = new Swift_Message();
	$message->setFrom("example@email.com"); // почта отправителя
	$message->addTo($email);
	$message->setSubject("confirmed email");
	$message->addPart(<<< _HTML_
<html>
	<body>
		<h3>Helloy $login</h3>
		<p>You want confirmed email?<p>
		<a href="http://email.rrr/confirmed_email/confirmed_email.php?code=$code">confirmed email</a>
	</body>
</html>
_HTML_, "text/html");
	// хост порт ssl/tls
	$transport = (new Swift_SmtpTransport('smtp.yandex.ru', 465, 'ssl'))
		// Логин отправителя
		->setUsername('UserName')
		// пароль от почты (можно сгенерировать пароль отдельно для этого)
		->setPassword('password');
	$mailer = new Swift_Mailer($transport);
	if ($mailer->send($message)) {
		global $DB;
		$pre = $DB->prepare('INSERT INTO `confirmed_email`(`email`, `code`) VALUES (:email,:code)');
		$pre->execute([":email" => $email, ":code" => $code]);
		return true;
	} else return false;
}
