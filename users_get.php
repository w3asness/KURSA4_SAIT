<?php
require_once 'config.php';

try {
	$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage();
	die();
}
$where = '';
//если передали логин/пароль
if (!empty($_GET['login']) && !empty($_GET['password'])) {
	$login = $_GET['login'];
	$pas = $_GET['password'];
	$where = sprintf("");
	$sql = sprintf('SELECT `ID`, `EMAIL`, `LOGIN` FROM `users` WHERE `LOGIN` LIKE \'%s\' AND `PASSWORD` LIKE \'%s\'', $login,$pas);
	$result = '{"response":[';
	$user = $db->query($sql)->fetch();
	//такой пользователь есть
	  if(!empty($user)) {
		$id = $user['ID'];
		$email = $user['EMAIL'];
		$login = $user['LOGIN'];
		$token = md5 (time());
		$expiration = time () + 24*60*60;
		$result .= sprintf('{"id":%d,"email":"%s","login":"%s","token":"%s","expiration":%d}',$id,$email,$login,$token,$expiration);
		
		//вставим токен и время его жизни в таблицу
		$sql_upd = sprintf('UPDATE `users` SET `TOKEN`=\'%s\',`EXPIRATION`=FROM_UNIXTIME(%d) WHERE `ID`=%d', $token,$expiration,$id);
		$db->exec($sql_upd);
	}
	$result = rtrim($result, ",");
	$result .= ']}';
	
	echo $result;
}
else {
	echo '{"error": {"text": "не передан логин/пароль"}}';
}
?>