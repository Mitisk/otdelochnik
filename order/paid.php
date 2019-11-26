<?php
session_start();
$_SESSION['msg_order'] = "Заказ успешно оплачен!";
   define('shoppromoauto', true);
   include("../include/dbconn.php");
$clear = mysql_query("DELETE FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}'",$link);

header("Location: http://промоавто.рф/profile.php");
?>