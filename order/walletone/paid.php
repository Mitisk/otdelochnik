<?php
session_start();
$_SESSION['msg_order'] = "Заказ успешно оплачен!";

header("Location: http://промоавто.рф/profile.php");
?>