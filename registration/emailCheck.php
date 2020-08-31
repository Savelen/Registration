<?php
require_once '../model/db.php';
// проверка логина
$pre = $DB->prepare('SELECT COUNT(`email`) FROM user WHERE `email` = :email');
$pre->execute([":email" => $_GET['email']]);
// получаем ответ и сразу отсылаем ответ
echo json_encode(array("respons" => $pre->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(`email`)"]));
