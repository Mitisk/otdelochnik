<?php
session_start();
$_SESSION['msg_order'] = "Не удалось оплатить заказ!";
header("Location: http://промоавто.рф/profile.php");
?>