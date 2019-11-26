<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
define('shoppromoauto', true);
include("dbconn.php");
include("../functions/sec.php");
include("../functions/password.php");

$email = clear_string($_POST["email"]);

if ($email != "")
{
    
   $result = mysql_query("SELECT mail FROM users WHERE mail='$email'",$link);
If (mysql_num_rows($result) > 0)
{
    
// Генерация пароля.
    $newpass = fungenpass();
    
// Шифрование пароля.
    $pass   = md5($newpass);
    $pass   = strrev($pass);
    $pass   = strtolower("7f6see".$pass."4t9");    
 
// Обновление пароля на новый.
$update = mysql_query ("UPDATE users SET password='$pass' WHERE mail='$email'",$link);

    
// Отправка нового пароля.
   
	         send_mail( 'noreply@shop.ru',
			             $email,
						'Новый пароль для сайта MyShop.ru',
						'Ваш пароль: '.$newpass);   
   
   echo 'yes';
    
}else
{
    echo 'Данный E-mail не найден!';
}

}
else
{
    echo 'Укажите свой E-mail';
}

}



?>