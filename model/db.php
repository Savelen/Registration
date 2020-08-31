<?php
try {
	$DB = new PDO(
		'mysql:host=email.rrr;dbname=email',
		'root',
		'',
		[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
	);
} catch (PDOException $e) {
	echo 'ошибка' . $e->getmessage();
}
