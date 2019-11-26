<?php
session_start();
ini_set('display_errors', 'Off');
if ($_SESSION['auth'] == 'yes_auth') {	
define('shoppromoauto', true);
include("include/dbconn.php");
include('functions/sec.php');
$id = clear_string($_GET["id"]);
$id_user = $_SESSION['auth_id'];  
$result = mysql_query("SELECT * FROM orders WHERE (order_id = '$id') AND (id_users='$id_user') AND (visible='yes')",$link);
if (mysql_num_rows($result) > 0) {	

  $action = clear_string($_GET["action"]);
	   $count = mysql_query("SELECT * FROM orders WHERE order_id = '$id' AND order_pay != 'accepted'",$link);
	   $countresult = mysql_num_rows($count);
  if (isset($action)) {
   switch ($action) {

       case 'delete':
	   if ($countresult > 0) {
	   $delete = mysql_query("DELETE FROM orders WHERE (id_users='$id_user') AND (order_id = '$id')",$link);
	   $delete = mysql_query("DELETE FROM buy_products WHERE (buy_id_order='$id')",$link);
	   } else {
	   $update = mysql_query("UPDATE orders SET visible='no' WHERE order_id = '$id'",$link);
	   }
		$_SESSION['msg_order'] = "Заказ удален!";
        header("Location: profile.php");   
	    break;
	} 
}
?>
<html>
 <head>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/profile.css" rel="stylesheet" type="text/css" />
	<link href="css/cart.css" rel="stylesheet" type="text/css" />

    
    <script type="text/javascript" src="/js/jquery-1.8.2.min.js"></script> 
    
	<title>Промоавто.рф</title>
</head>
<body>
<div id="wrapper">
 <?php include("include/header.php"); ?>
  <?php include("include/slider.php"); ?>
 <section class="content normal">
<h2>Просмотр заказа</h2>
<?php
$result = mysql_query("SELECT * FROM orders WHERE order_id = '$id' AND id_users='$id_user'",$link);

 If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
do {
if ($row["order_confirmed"] == 'yes') {
    $status = '<span class="green">Заказ завершен</span>';
} elseif ($row["order_confirmed"] == 'question') {
	$status = '<span class="yellow">Есть вопрос по заказу</span>';
} elseif ($row["order_confirmed"] == 'nopay') {
	$status = '<span class="yellow">Ожидается оплата заказа</span>';
} elseif ($row["order_confirmed"] == 'delivery') {
	$status = '<span class="yellow">Передан в службу доставки</span>';
} else {
    $status = '<span class="red">Не обработан</span>';    
}

 echo '
<TABLE align="center" CELLPADDING="10" WIDTH="100%">
<TR>
<TH>№</TH>
<TH>Наименование товара</TH>
<TH>Цена</TH>
<TH>Количество</TH>
</TR>
';

$query_product = mysql_query("SELECT * FROM buy_products,products WHERE buy_products.buy_id_order = '$id' AND products.id = buy_products.buy_id_product",$link);
 
$result_query = mysql_fetch_array($query_product);
do {
$price = $price + ($result_query["price"] * $result_query["buy_count_product"]);    
$index_count =  $index_count + 1; 
echo '
<TR>
<TD  align="CENTER" >'.$index_count.'</TD>
<TD  align="CENTER" ><a href="../product.php?id='.$result_query[id].'">'.$result_query["title"].'</a></TD>
<TD  align="CENTER" >'.$result_query["price"].' руб</TD>
<TD  align="CENTER" >'.$result_query["buy_count_product"].'</TD>
</TR>
';
} while ($result_query = mysql_fetch_array($query_product));



if ($row["order_type_pay"] == "p2p-incoming") {
    $order_type_pay = 'Яндекс.Деньги';
} elseif ($row["order_type_pay"] == "card-incoming") {
    $order_type_pay = 'Банковская карта';
} else {$order_type_pay = 'Не удалось определить';}

if ($row["order_pay"] == "accepted") {
    $statpay = '<span class="green">Оплачено</span></li>
                <li><strong>Тип оплаты</strong> - <span>'.$order_type_pay.'</span></li>
                <li><strong>Дата оплаты</strong> - <span>'.$row["order_datetime_pay"].'</span>';
	$payorder = '';
} else {
    $statpay = '<span class="red">Не оплачено</span>';
	$payorder = '<style>input[type=\'submit\'] {width: 100%;}</style>
	<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml"> 
    <input type="hidden" name="receiver" value="410013010205893"> 
    <input type="hidden" name="formcomment" value="Оплата заказа № '.$row["order_id"].'"> 
    <input type="hidden" name="short-dest" value="Оплата заказа № '.$row["order_id"].'"> 
    <input type="hidden" name="label" value="'.$row["order_id"].'"> 
    <input type="hidden" name="quickpay-form" value="donate"> 
    <input type="hidden" name="targets" value="транзакция '.$row["order_id"].'"> 
    <input type="hidden" name="sum" value="'.$price.'" data-type="number"> 
    <input type="hidden" name="comment" value="Оплата заказа в магазине промоавто.рф."> 
	<input type="hidden" name="successURL" value="http://xn--80ae2ahbcbix.xn--p1ai/order/paid.php"> 
    <input type="hidden" name="need-fio" value="false"> 
    <input type="hidden" name="need-email" value="false"> 
    <input type="hidden" name="need-phone" value="false"> 
    <input type="hidden" name="need-address" value="false"> 
	<label class="payvisa"><input type="radio" name="paymentType" value="AC" checked><span></span></label>
	<label class="paymsc"><input type="radio" name="paymentType" value="AC"><span></span></label>
    <label class="payyandex"><input type="radio" name="paymentType" value="PC"><span></span></label>
	<input type="submit" value="Оплатить">
</form>';
}


echo '
</TABLE>
<div class="containerforpay">
 <div id="pageleft">
<h3 class="title-h3" >Информация:</3>
<ul id="info">
<li><strong>Дата заказа</strong> - <span>'.$row["order_datetime"].'</span></li>
<li><strong>Номер заказа</strong> - <span>'.$row["order_id"].'</span></li>
<li><strong>Статус заказа</strong> - <span>'.$status.'</span></li>
<li><strong>Общая цена</strong> - <span>'.$price.'</span> руб</li>
<li><strong>Способ доставки</strong> - <span>'.$row["order_dostavka"].'</span></li>
<li><strong>Статус оплаты</strong> - '.$statpay.'</li>
</ul>

</div><div id="pageright">
<h3 class="title-h3" >Оплата заказа:</3>
<ul id="info">
'.$payorder.'
</ul></div><div class="clearer"></div></div>

<TABLE align="center" CELLPADDING="10" WIDTH="100%">
<TR>
<TH>ФИО</TH>
<TH>Адрес</TH>
<TH>Контакты</TH>
<TH>Примечание</TH>
</TR>

 <TR>
<TD  align="CENTER" >'.$row["order_fio"].'</TD>
<TD  align="CENTER" >'.$row["order_address"].'</TD>
<TD  align="CENTER" ><a href="/profile.php?id='.$row["id_users"].'">профиль</a></br>'.$row["order_phone"].'</br>'.$row["order_email"].'</TD>
<TD  align="CENTER" >'.$row["order_note"].'</TD>
</TR>
</TABLE>
<br>
<a href="view_order.php?id='.$row["order_id"].'&action=delete" class="delete">Удалить заказ</a></p>
 
 ';   

    
} while ($row = mysql_fetch_array($result));
}
?>
 </section>
 <?php include("include/footer.php"); ?>
 </div>

</body>
</html>
<?php
} else { header("Location: index.php");  }	
} else { header("Location: index.php");  }	
?>