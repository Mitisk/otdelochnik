<?php
defined('shoppromoauto') or die ('Загрузка...');
$id_user = $_SESSION['auth_id'];    
$result = mysql_query("SELECT * FROM orders WHERE (id_users='$id_user') AND (visible='yes') ORDER BY order_id DESC",$link);
 
 If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
do
{
if ($row["order_confirmed"] == 'yes')
{
    $status = 'Заказ завершен';
	
} elseif ($row["order_confirmed"] == 'question') {
	$status = '<span class="red">Есть вопрос по заказу</span>';
} elseif ($row["order_confirmed"] == 'nopay') {
	$status = '<span class="yellow">Ожидается оплата заказа</span>';
} elseif ($row["order_confirmed"] == 'delivery') {
	$status = 'Передан в службу доставки';
} else {
    $status = '<span class="red">Не обработан</span>';    
}

if ($row["order_pay"] == "accepted") {
    $statpay = '<span class="green">Оплачено</span>';
} else {
    $statpay = '<span class="red">Не оплачено</span>';
}
  
 echo '
 <div class="block-order">
 
  
  <p class="order-number" >Заказ № '.$row["order_id"].' - '.$status.'</p>
  <p class="order-datetime" >'.$row["order_datetime"].' - '.$statpay.'</p>
  <p class="order-link" ><a class="green" href="view_order.php?id='.$row["order_id"].'" >Подробнее</a></p>
  <p class="order-delete" ><a href="view_order.php?id='.$row["order_id"].'&action=delete"><img src="/images/bsk_item_del.png"></a></p>
 </div>
 ';   
    
} while ($row = mysql_fetch_array($result));
}
?>
