<?php/*
if($_SERVER["REQUEST_METHOD"] == "POST") {
define('shoppromoauto', true);
include("../include/dbconn.php");
include("../functions/sec.php");
    
$id_order = clear_string($_POST["label"]);
//$status_pay = strtolower(clear_string($_POST["WMI_ORDER_STATE"]));
$order_type_pay = clear_string($_POST["notification_type"]);
$nomer_zakaza = clear_string($_POST["operation_id"]);
if(($_POST["unaccepted"] != 'true') and ($_POST["codepro"] != 'true')) {$status_pay = 'accepted';} else {$status_pay = 'nopay';}
//$update = mysql_query("UPDATE orders SET order_pay='$status_pay',order_type_pay='$order_type_pay',nomer_zakaza='$nomer_zakaza' WHERE order_id='$id_order'",$link);
$update = mysql_query("UPDATE orders SET order_pay='$status_pay',order_type_pay='$order_type_pay',order_num='$nomer_zakaza' WHERE order_id='$id_order'",$link);
} */
?>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
define('shoppromoauto', true);
include("../include/dbconn.php");
include("../functions/sec.php");

$secret = 'yGHZKnv8woe7yDfloAupucel'; // секретный ключ от яндекс.
// получение данных.
$r = array(
	'notification_type' => $_POST['notification_type'], // p2p-incoming / card-incoming - с кошелька / с карты
	'operation_id'      => $_POST['operation_id'],      // Идентификатор операции в истории счета получателя.
	'amount'            => $_POST['amount'],            // Сумма, которая зачислена на счет получателя.
	'withdraw_amount'   => $_POST['withdraw_amount'],   // Сумма, которая списана со счета отправителя.
	'currency'          => $_POST['intval'],            // Код валюты — всегда 643 (рубль РФ согласно ISO 4217).
	'datetime'          => $_POST['datetime'],          // Дата и время совершения перевода.
	'sender'            => $_POST['sender'],            // Для переводов из кошелька — номер счета отправителя. Для переводов с произвольной карты — параметр содержит пустую строку.
	'codepro'           => $_POST['codepro'],           // Для переводов из кошелька — перевод защищен кодом протекции. Для переводов с произвольной карты — всегда false.
	'label'             => $_POST['label'],             // Метка платежа. Если ее нет, параметр содержит пустую строку.
	'sha1_hash'         => $_POST['sha1_hash']          // SHA-1 hash параметров уведомления.
);

// проверка хеш
/*if (sha1($r['notification_type'].'&'.
         $r['operation_id'].'&'.
         $r['amount'].'&'.
         $r['currency'].'&'.
         $r['datetime'].'&'.
         $r['sender'].'&'.
         $r['codepro'].'&'.
         $secret.'&'.
         $r['label']) != $r['sha1_hash']) {
	exit('Верификация не пройдена! SHA1_HASH не совпадает.'); // останавливаем скрипт. 
}*/

// обработаем данные.
$id_order = intval($r['label']); // Передача id заказа, поэтому обрабатываю его intval.
$datetime = $r['datetime'];
$status_pay = "accepted";
$order_type_pay = $r['notification_type'];
$nomer_zakaza = intval($r['operation_id']);


// Обновление БД
$update = mysql_query("UPDATE orders SET order_datetime_pay='$datetime',order_pay='$status_pay',order_type_pay='$order_type_pay',order_num='$nomer_zakaza' WHERE order_id='$id_order'",$link);
} 
?>