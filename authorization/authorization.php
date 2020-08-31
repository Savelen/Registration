<?php
require_once '../model/db.php';

try {
	$login = $_POST['login'];
	$password = $_POST['password'];
	// для ошибок
	$err = [];
	// проверка на валидность пароля и логина
	if (iconv_strlen($password) < 6) {
		array_push($err, "ошибка в парроле: Пароль не должен быть короче 6 символов.");
	}

	// проверка наличие ошибок и их вывод
	if (count($err) > 0) {
		$message = "<br>";
		foreach ($err as $value) {
			$message .= "$value<br>";
		}
		throw new Exception($message);
	}

	// достаём из бд (если есть) инфу о пользователе
	$pre = $DB->prepare('SELECT `login`,`password` FROM user WHERE `login` = :login or `email` = :login');
	$pre->execute([":login" => $login]);
	$response = $pre->fetchAll(PDO::FETCH_ASSOC)[0];
	if (empty($responsel) && password_verify($password, $response['password'])) {
		echo "Вы авторизованны";
	} else echo "Неверен логин или пароль";
} catch (Exception $e) {
	echo "Ошибка!" . $e->getMessage();
}
