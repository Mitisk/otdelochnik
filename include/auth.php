<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	define('shoppromoauto', true);
	include('dbconn.php');
    include('../functions/sec.php');
    
    $login = clear_string($_POST["login"]);
    
    $pass   = md5(clear_string($_POST["pass"]));

    $pass   = strrev($pass);

    $pass   = strtolower("7f6see".$pass."4t9");

    setcookie('promoauto',$login.'+'.$pass,time()+3600*24*31, "/");
       
   $result = mysql_query("SELECT * FROM users WHERE (login = '$login' OR mail = '$login') AND password = '$pass'",$link);
If (mysql_num_rows($result) > 0)
{
    $row = mysql_fetch_array($result);
    session_start();
    $_SESSION['auth'] = 'yes_auth'; 
	$_SESSION['auth_id'] = $row["id"];
    $_SESSION['auth_pass'] = $row["password"];
    $_SESSION['auth_login'] = $row["login"];
    $_SESSION['auth_surname'] = $row["surname"];
    $_SESSION['auth_name'] = $row["name"];
    $_SESSION['auth_address'] = $row["address"];
    $_SESSION['auth_phone'] = $row["phone"];
    $_SESSION['auth_email'] = $row["mail"];
    echo 'yes_auth';

}else
{
    echo 'no_auth';
}  
}
?>