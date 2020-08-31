<?php
require_once "../model/db.php";
require_once '../model/db.php';
try {
	if (isset($_GET['code'])) {
		$pre = $DB->prepare('SELECT `email` FROM `confirmed_email` WHERE `code` =:code');
		$pre->execute([":code" => $_GET['code']]);
		$email = $pre->fetchAll(PDO::FETCH_ASSOC)[0]['email'];
		if (!empty($email)) {
			echo $email;
			// потверждаем почту и удаляем проверку
			$pre =  $DB->prepare("UPDATE `user` SET `confirmed_email` = 1 WHERE `email` = :email; DELETE FROM `confirmed_email` WHERE `email`= :email;");
			$pre->execute([":email" => $email]);
		} else throw new Exception("Почта не найдена или уже проверена");
	} else throw new Exception("Нет данных");
} catch (Exception $e) {
	echo "ошибка! " . $e->getMessage();
}
