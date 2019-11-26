<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{ 
define('shoppromoauto', true);
session_start();
include("../include/dbconn.php");
include("../functions/sec.php");
$error = array();
$login = iconv("UTF-8", "cp1251", strtolower(clear_string($_POST['login'])));
$sname = iconv("UTF-8", "cp1251", strtolower(clear_string($_POST['surname'])));
$name = iconv("UTF-8", "cp1251", strtolower(clear_string($_POST['name'])));
$mail = iconv("UTF-8", "cp1251", strtolower(clear_string($_POST['mail'])));
$pass = iconv("UTF-8", "cp1251", strtolower(clear_string($_POST['password'])));
if (strlen($login) < 2 or strlen($login) > 16) {
	$error[] = "Логин должен быть от 2 до 16 символов!";
} else {
	$result = mysql_query("SELECT login FROM users WHERE login = '$login'",$link);
	if (mysql_num_rows($result) > 0) {
		$error[] = "Логин занят!";
	}
}
if (strlen($pass) < 5 or strlen($pass) > 16) $error[] = "Пароль должен быть от 5 до 16 символов!";
if (strlen($sname) < 2 or strlen($sname) > 20) $error[] = "Фамилия должно быть от 2 до 20 символов!";
if (strlen($name) < 2 or strlen($name) > 20) $error[] = "Имя должно быть от 2 до 20 символов!";
if (!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", trim($mail))) $error[] = "Введите верный e-mail!";
	//reCAPTCHA
	define( API_PUBLIC_KEY, '6LdilhwTAAAAAKJKg4WROGEz8ybOge14XRMBAj0g' );
	define( API_PRIVATE_KEY, '6LdilhwTAAAAAP_fIwGfPNMZ6uk1mE1WGSO5FYGw'  );
	require_once('/recaptchalib.php');
	if( API_PRIVATE_KEY == 'private_key_goes_here' || strlen(API_PRIVATE_KEY) < 39 ||
		API_PUBLIC_KEY == 'public_key_goes_here' || strlen(API_PUBLIC_KEY) < 39 
															 ) {
		die($error[] = 'Критическая ошибка с ключами! Пожалуйста, обратитесь к администрации!');
	}
	if( $_POST['validate'] === 'yes' ) { 
		$response = recaptcha_check_answer( API_PRIVATE_KEY,
										   	$_SERVER['REMOTE_ADDR'],
											$_POST['recaptcha_challenge_field'],
											$_POST['recaptcha_response_field']
											);
		
		if( ! $response->is_valid ) {		
			$error[] = "Неверная reCAPTCHA";
		} else {
			$validated = true;

		}	/* end if( ! is_valid ) */
	
	 } /* end if($_POST['validate']==='yes') */ 

//if ($_SESSION['g-recaptcha-response'] != true) $error[] = "Неверная капча!";
//unset($_SESSION['g-recaptcha-response']);
if (count($error)) {
	echo implode('<br>',$error);
} else {
	$pass = md5($pass);
	$pass = strrev($pass);
	$pass = "7f6see".$pass."4t9";
	$ip = $_SERVER['REMOTE_ADDR'];
	mysql_query("INSERT INTO users(login, password, name, surname, mail, datetime, ip) VALUES(
	'".$login."',
	'".$pass."',
	'".$name."',
	'".$sname."',
	'".$mail."',
	NOW(),
	'".$ip."'
	)",$link);
	echo 'true';
}
}
?>