<?php
require_once '../model/db.php';
// проверка логина
$pre = $DB->prepare('SELECT COUNT(`login`) FROM user WHERE `login` = :login');
$pre->execute([":login" => $_GET['login']]);
// получаем ответ и сразу отсылаем ответ
echo json_encode(array("respons" => $pre->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(`login`)"]));
