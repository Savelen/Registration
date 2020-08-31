<?php
require_once '../model/db.php'; // бд
require_once '../confirmed_email/send_email.php'; // проверка почты

$login = $_POST["login"];
$password = $_POST["password"];
$email = $_POST["email"];
// наличие ошбки
$err = [];
// проверка валидности данных
try {
	// проверка логина
	if (preg_match("/[^\w]/", $login) ||  iconv_strlen($login) < 6) {
		array_push($err, "ошибка в логине:" . "<br>   " . "Допустимые символы: " . (!preg_match("/[^\w]/", $login) ? "Да" : "Нет") . "<br>   " . "Длина строки больше 5: " . (!iconv_strlen($login) < 6 ? "Да" : "Нет") . "<br>-------------------------");
	} else {
		$pre = $DB->prepare('SELECT COUNT(`login`) FROM user WHERE `login` = :login');
		$pre->execute([":login" => $login]);
		if ($pre->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(`login`)"] == 1) {
			array_push($err, "логин занят");
		}
	}
	// проверка почты
	if (!preg_match("/@/", $email) || iconv_strlen($email) < 5) {
		// array_push($err, "ошибка в почте");
		array_push($err, "ошибка в почте:" . "<br>   " . "Символ @: " . (preg_match("/@/", $email) ? "Есть" : "Нет") . "<br>   " . "Длина строки больше 4: " . (iconv_strlen($email) > 4 ? "Да" : "Нет") . "<br>-------------------------");
	} else {
		$pre = $DB->prepare('SELECT COUNT(`email`) FROM user WHERE `email` = :email');
		$pre->execute([":email" => $email]);
		if ($pre->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(`email`)"] == 1) {
			array_push($err, "почта занята");
		}
	}
	// проверяем пароль на валидность
	if (iconv_strlen($password) < 6) {
		array_push($err, "ошибка в парроле: Он не должен быть короче 6 символов.");
	}
	// проверка наличие ошибок и их вывод
	if (count($err) > 0) {
		// $message = "Ошибка!<br>";
		$message = "<br>";
		foreach ($err as $value) {
			$message .= "$value<br>";
		}
		throw new Exception($message);
	}

	// хешируем пароль и вслучае ошибки выводим исключение (настроить под себя)
	$password = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 6, "threads" => 2, "memory_cost" => 8192]);
	if (!$password) {
		array_push($err, "ошибка при хэширование пароля:" . "<br>");
	}
	// отправляем всё в бд
	if (confirmed_email($email,$password,$login)){
		$pre = $DB->prepare('INSERT INTO `user`(login,password,email) VALUES (:login,:password,:email)');
	$pre->execute([":login" => $login, ":password" => $password, ":email" => $email]);
	} else throw new Exception("Ошибка при отправке письма, попробуйте сново.");

} catch (Exception $e) {
	echo "Ошибка! ", $e->getMessage();
}
//
//
//
//
// В моём случае ставить 2 ядра время 6 память 8192 скорость 25 хешей\сек
//
//
//
//
//
