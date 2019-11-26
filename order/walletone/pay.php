<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
define('shoppromoauto', true);
include("../include/dbconn.php");
include("../functions/sec.php");
    
$id_order = clear_string($_POST["WMI_PAYMENT_NO"]);
$status_pay = strtolower(clear_string($_POST["WMI_ORDER_STATE"]));
$order_type_pay = clear_string($_POST["WMI_PAYMENT_TYPE"]);
$nomer_zakaza = clear_string($_POST["WMI_ORDER_ID"]);

$update = mysql_query("UPDATE orders SET order_pay='$status_pay',order_type_pay='$order_type_pay',nomer_zakaza='$nomer_zakaza' WHERE order_id='$id_order'",$link);

echo 'WMI_RESULT=OK&WMI_DESCRIPTION=Заказ успешно оплачен!';
} 
?>