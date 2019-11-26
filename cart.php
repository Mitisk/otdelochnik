<?php
 session_start();
 define('shoppromoauto', true);
 ini_set('display_errors', 'Off');
 include("include/dbconn.php");
 include('functions/sec.php');
 include('functions/cartprice.php');
 include("include/auth_cookie.php");
    $id = clear_string($_GET["id"]);
     $action = clear_string($_GET["action"]);
    
   switch ($action) {

	    case 'clear':
        $clear = mysql_query("DELETE FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}'",$link);     
	    break;
        
        case 'delete':     
        $delete = mysql_query("DELETE FROM cart WHERE cart_id = '$id' AND cart_ip = '{$_SERVER['REMOTE_ADDR']}'",$link);        
        break;
        
	}
    
if (isset($_POST["submitdata"]))
{
if ( $_SESSION['auth'] == 'yes_auth' ) 
 {
if ( $_SESSION['auth_patronymic'] == '' ) $_SESSION["auth_patronymic"] = $_POST["auth_patronymic"];
if ( $_SESSION['auth_phone'] == '' ) $_SESSION["auth_phone"] = $_POST["auth_phone"];
if ( $_SESSION['auth_address'] == '' ) $_SESSION["auth_address"] = $_POST["auth_address"];

$_SESSION["order_delivery"] = $_POST["order_delivery"];
$_SESSION["order_note"] = $_POST["order_note"];
        
    mysql_query("INSERT INTO orders(id_users,order_datetime,order_dostavka,order_fio,order_address,order_phone,order_note,order_email)
						VALUES(	
                            '".$_SESSION["auth_id"]."',	
							NOW(),
                            '".$_POST["order_delivery"]."',					
							'".$_SESSION['auth_surname'].' '.$_SESSION['auth_name'].' '.$_SESSION['auth_patronymic']."',
                            '".$_SESSION['auth_address']."',
                            '".$_SESSION['auth_phone']."',
                            '".$_POST['order_note']."',
                            '".$_SESSION['auth_email']."'                              
						    )",$link);         

 } else {
$_SESSION["order_delivery"] = $_POST["order_delivery"];
$_SESSION["order_fio"] = $_POST["order_fio"];
$_SESSION["order_email"] = $_POST["order_email"];
$_SESSION["order_phone"] = $_POST["order_phone"];
$_SESSION["order_address"] = $_POST["order_address"];
$_SESSION["order_note"] = $_POST["order_note"];

    mysql_query("INSERT INTO orders(order_datetime,order_dostavka,order_fio,order_address,order_phone,order_note,order_email)
						VALUES(	
                             NOW(),
                            '".clear_string($_POST["order_delivery"])."',					
							'".clear_string($_POST["order_fio"])."',
                            '".clear_string($_POST["order_address"])."',
                            '".clear_string($_POST["order_phone"])."',
                            '".clear_string($_POST["order_note"])."',
                            '".clear_string($_POST["order_email"])."'                   
						    )",$link);    
 }

                          
 $_SESSION["order_id"] = mysql_insert_id();                          
                            
$result = mysql_query("SELECT * FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}'",$link);
If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);    

do{

    mysql_query("INSERT INTO buy_products(buy_id_order,buy_id_product,buy_count_product)
						VALUES(	
                            '".$_SESSION["order_id"]."',					
							'".$row["cart_id_product"]."',
                            '".$row["cart_count"]."'                   
						    )",$link);



} while ($row = mysql_fetch_array($result));
}
header("Location: cart.php?action=completion");
}  


$result = mysql_query("SELECT * FROM cart,products WHERE cart.cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND products.id = cart.cart_id_product",$link);
If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);

do
{ 
$int = $int + ($row["price"] * $row["cart_count"]); 
}
 while ($row = mysql_fetch_array($result));
   $itogpricecart = group_numerals($int);
   $_SESSION["itog_price"] = $int;
}     
?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="css/style.css" type="text/css" />
	 <link rel="stylesheet" href="css/reset.css" type="text/css" />
     <link rel="stylesheet" href="css/modal.css" type="text/css" />
	 <link rel="stylesheet" href="css/cart.css" type="text/css" />
     <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
     <script type="text/javascript" src='../js/jquery-1.8.2.min.js'></script>
	 <script type="text/javascript" src='../js/incart.js'></script>
  <title>Промоавто.рф</title>
 </head>
 <body>
 <div id="wrapper">
 <?php include("include/header.php"); ?>
 <section class="content normal">

<?php

  $action = clear_string($_GET["action"]);
  switch ($action) {

	    case 'oneclick':
   
   $result = mysql_query("SELECT * FROM cart,products WHERE cart.cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND products.id = cart.cart_id_product",$link);

If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
   echo ' 
   <div id="block-step">  
   <div id="name-step">  
   <ul>
   <li><a class="active" >1. Корзина товаров</a></li>
   <li><span>&rarr;</span></li>
   <li><a>2. Контактная информация</a></li>
   <li><span>&rarr;</span></li>
   <li><a>3. Завершение</a></li> 
   </ul>  
   </div>  
   <p>шаг 1 из 3</p>
   </div>
';
  
   


   echo '  
   <div id="header-list-cart">    
   <div id="head1" >Изображение</div>
   <div id="head2" >Наименование товара</div>
   <div id="head3" >Кол-во</div>
   <div id="head4" >Цена</div>
   <div id="head5" ><a href="cart.php?action=clear" >Очистить</a></div>
   </div> 
   ';

do
{

$int = $row["cart_price"] * $row["cart_count"];
$all_price = $all_price + $int;

if  (strlen($row["image"]) > 0 /*&& file_exists('../upload_images/'.$row[image])*/)
{
$img_path = '../upload_images/'.$row[image];
$width = 125;
$height = 125;
} else {
$img_path = "../images/noimage.jpg";
$width = 125;
$height = 125;
}

echo '

<div class="block-list-cart">

<div class="img-cart">
<p align="center"><img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" /></p>
</div>

<div class="title-cart">
<p><a href="../product.php?id='.$row[id].'">'.$row["title"].'</a></p>
<p class="cart-mini-features">

</p>
</div>

<div class="count-cart">
<ul class="input-count-style">

<li>
<p align="center" onclick="CountMinus('.$row[cart_id].');" class="count-minus">-</p>
</li>

<li>
<p align="center"><input id="input-id'.$row[cart_id].'" class="count-input" maxlength="3" type="text" value="'.$row["cart_count"].'" disabled /></p>
</li>

<li>
<p align="center" onclick="CountPlus('.$row[cart_id].');" class="count-plus">+</p>
</li>

</ul>
</div>

<div align="center" id="tovar'.$row["cart_id"].'" class="price-product"><h5><span class="span-count" >'.$row["cart_count"].'</span> x <span>'.$row["cart_price"].'</span></h5><p price="'.$row["cart_price"].'" >'.group_numerals($int).' руб</p></div>
<div class="delete-cart" align="center"><a  href="cart.php?id='.$row["cart_id"].'&action=delete" ><img src="/images/bsk_item_del.png" /></a></div>

<div id="bottom-cart-line"></div>
</div>


';

    
}
 while ($row = mysql_fetch_array($result));
 
 echo '
 <h3 class="itog-price" align="right">Итого: <strong>'.group_numerals($all_price).'</strong> руб</h3>
 <p align="right" class="button-next" ><a href="cart.php?action=confirm" >Далее</a></p> 
 ';
  
} 
else
{
    echo '<h3 id="clear-cart" align="center">Корзина пуста</h3>';
}


   
	    break;
        
        case 'confirm':     
     
    echo ' 
   <div id="block-step"> 
   <div id="name-step">  
   <ul>
   <li><a href="cart.php?action=oneclick" >1. Корзина товаров</a></li>
   <li><span>&rarr;</span></li>
   <li><a class="active" >2. Контактная информация</a></li>
   <li><span>&rarr;</span></li>
   <li><a>3. Завершение</a></li> 
   </ul>  
   </div> 
   <p>шаг 2 из 3</p>

   </div>

   '; 
   

if ($_SESSION['order_delivery'] == "По почте") $chck1 = "checked";
if ($_SESSION['order_delivery'] == "Курьером") $chck2 = "checked";
if ($_SESSION['order_delivery'] == "Самовывоз") $chck3 = "checked"; 
 
 echo '
<div id="pageleft">
<h3 class="title-h3" >Способы доставки:</h3><br>
<form method="post">
<ul id="info">
<li>
<input type="radio" name="order_delivery" class="order_delivery" id="order_delivery1" value="По почте" '.$chck1.'  />
<label class="label_delivery" for="order_delivery1">По почте</label>
</li>
<br>
<li>
<input type="radio" name="order_delivery" class="order_delivery" id="order_delivery2" value="Курьером" '.$chck2.' />
<label class="label_delivery" for="order_delivery2">Курьером</label>
</li>
<br>
<li>
<input type="radio" name="order_delivery" class="order_delivery" id="order_delivery3" value="Самовывоз" '.$chck3.' />
<label class="label_delivery" for="order_delivery3">Самовывоз</label>
</li>
</ul>
</div><div id="pageright">
<h3 class="title-h3" >Информация для доставки:</h3>
<br>
<ul id="info">
';
  if ( $_SESSION['auth'] != 'yes_auth' ) 
{
echo '
<li><label id="orderlabel" for="order_fio">ФИО </label><input type="text" name="order_fio" id="order_fio" value="'.$_SESSION["order_fio"].'" /></li>
<li><label id="orderlabel" for="order_email">E-mail</label><input type="text" name="order_email" id="order_email" value="'.$_SESSION["order_email"].'" /></li>
<li><label id="orderlabel" for="order_phone">Телефон</label><input type="text" name="order_phone" id="order_phone" value="'.$_SESSION["order_phone"].'" /></li>
<li><label id="orderlabel" class="order_label_style" for="order_address">Адрес доставки</label><input type="text" name="order_address" id="order_address" value="'.$_SESSION["order_address"].'" /></li>
';
} else {
if ( $_SESSION['auth_patronymic'] == '' ) echo '<li><label id="orderlabel" for="auth_patronymic">Отчество</label><input type="text" name="auth_patronymic" id="auth_patronymic" value="'.$_SESSION["auth_patronymic"].'" /></li>'; $_SESSION["auth_patronymic"] = $_POST["auth_patronymic"];
if ( $_SESSION['auth_phone'] == '' ) echo '<li><label id="orderlabel" for="auth_phone">Телефон</label><input type="text" name="auth_phone" id="auth_phone" value="'.$_SESSION["auth_phone"].'" /></li>'; $_SESSION["auth_phone"] = $_POST["auth_phone"];
if ( $_SESSION['auth_address'] == '' ) echo '<li><label id="orderlabel" for="auth_address">Адрес доставки</label><input type="text" name="auth_address" id="auth_address" value="'.$_SESSION["auth_address"].'" /></li>'; $_SESSION["auth_address"] = $_POST["auth_address"];
}
echo '
<li><label id="orderlabel" class="order_label_style" for="order_note">Примечание</label><textarea name="order_note" placeholder="Уточните информацию о заказе. Например, удобное время для звонка нашего менеджера" >'.$_SESSION["order_note"].'</textarea></li>
</ul>
</div>
<p align="right" ><input type="submit" name="submitdata" id="confirm-button-next" value="Далее" /></p>
</form>



 ';      
      
        break;
		
	        
        case 'completion': 

    echo ' 
   <div id="block-step"> 
   <div id="name-step">  
   <ul>
   <li><a href="cart.php?action=oneclick" >1. Корзина товаров</a></li>
   <li><span>&rarr;</span></li>
   <li><a href="cart.php?action=confirm" >2. Контактная информация</a></li>
   <li><span>&rarr;</span></li>
   <li><a class="active" >3. Завершение</a></li> 
   </ul>  
   </div> 
   <p>шаг 3 из 3</p>

   </div>
   <div class="containerforpay">
 <div id="pageleft">
<h3 class="title-h3" >Конечная информация:</h3>
   '; 

if ( $_SESSION['auth'] == 'yes_auth' ) {

echo '
<ul id="info" >
<li><strong>Способ доставки: </strong>'.$_SESSION['order_delivery'].'</li>
<li><strong>Email: </strong>'.$_SESSION['auth_email'].'</li>
<li><strong>ФИО: </strong>'.$_SESSION['auth_surname'].' '.$_SESSION['auth_name'].' '.$_SESSION['auth_patronymic'].'</li>
<li><strong>Адрес доставки: </strong>'.$_SESSION['auth_address'].'</li>
<li><strong>Телефон: </strong>'.$_SESSION['auth_phone'].'</li>
<li><strong>Примечание: </strong>'.$_SESSION['order_note'].'</li>
</ul>

';
} else {
echo '
<ul id="info" >
<li><strong>Способ доставки: </strong>'.$_SESSION['order_delivery'].'</li>
<li><strong>Email: </strong>'.$_SESSION['order_email'].'</li>
<li><strong>ФИО: </strong>'.$_SESSION['order_fio'].'</li>
<li><strong>Адрес доставки: </strong>'.$_SESSION['order_address'].'</li>
<li><strong>Телефон: </strong>'.$_SESSION['order_phone'].'</li>
<li><strong>Примечание: </strong>'.$_SESSION['order_note'].'</li>
</ul>
';    
} echo '

</div><div id="pageright">
<h3 class="title-h3" >Оплата заказа:</h3>
<ul id="info">
<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml"> 
    <input type="hidden" name="receiver" value="410013010205893"> 
    <input type="hidden" name="formcomment" value="Оплата заказа № '.$_SESSION["order_id"].'"> 
    <input type="hidden" name="short-dest" value="Оплата заказа № '.$_SESSION["order_id"].'"> 
    <input type="hidden" name="label" value="'.$_SESSION["order_id"].'"> 
    <input type="hidden" name="quickpay-form" value="donate"> 
    <input type="hidden" name="targets" value="транзакция '.$_SESSION["order_id"].'"> 
    <input type="hidden" name="sum" value="'.$_SESSION["itog_price"].'" data-type="number"> 
    <input type="hidden" name="comment" value="Оплата заказа в магазине промоавто.рф."> 
	<input type="hidden" name="successURL" value="http://xn--80ae2ahbcbix.xn--p1ai/order/paid.php"> 
    <input type="hidden" name="need-fio" value="false"> 
    <input type="hidden" name="need-email" value="false"> 
    <input type="hidden" name="need-phone" value="false"> 
    <input type="hidden" name="need-address" value="false"> 
	<label class="payvisa"><input type="radio" name="paymentType" value="AC" checked><span></span></label>
	<label class="paymsc"><input type="radio" name="paymentType" value="AC"><span></span></label>
    <label class="payyandex"><input type="radio" name="paymentType" value="PC"><span></span></label>


	</ul>
    </div><div class="clearer"></div>
	 <h3 class="itog-price" align="right">Итого: <strong>'.$itogpricecart.'</strong> руб</h3> <input type="submit" align="right" value="Оплатить">
</form>
</div>






  
 
 '; 
 /*
 	<li><input type="radio" name="paymentType" value="AC" class="payvisa" id="visa"><label class="label_delivery" for="visa">VISA</label></li><br>
	<li><input type="radio" name="paymentType" value="AC" class="paymsc" id="msc"><label class="label_delivery" for="msc">MasterCard</label></li><br>
    <li><input type="radio" name="paymentType" value="PC" class="payyandex" id="yandex"><label class="label_delivery" for="yandex">Яндекс.Деньги</label></li>
 
 <form method="post" action="https://wl.walletone.com/checkout/checkout/Index">
  <input type="hidden" name="WMI_MERCHANT_ID"    value="109385595550"/>
  <input type="hidden" name="WMI_PAYMENT_AMOUNT" value="'.$_SESSION["itog_price"].'"/>
  <input type="hidden" name="WMI_PAYMENT_NO"     value="'.$_SESSION["order_id"].'"/>
  <input type="hidden" name="WMI_CURRENCY_ID"    value="643"/>
  <input type="hidden" name="WMI_DESCRIPTION"    value="Оплата заказа № '.$_SESSION["order_id"].'"/>
  <input type="hidden" name="WMI_SUCCESS_URL"    value="http://промоавто.рф/order/walletone/paid.php"/>
  <input type="hidden" name="WMI_FAIL_URL"       value="http://промоавто.рф/order/walletone/fail.php"/>
  <input type="submit"/>
</form>
<p align="right" class="button-next" ><a href="" >Оплатить</a></p> */

		
        break;
        
	    default:  
		   
   echo ' 
   <div id="block-step">  
   <div id="name-step">  
   <ul>
   <li><a class="active" >1. Корзина товаров</a></li>
   <li><span>&rarr;</span></li>
   <li><a>2. Контактная информация</a></li>
   <li><span>&rarr;</span></li>
   <li><a>3. Завершение</a></li> 
   </ul>  
   </div>  
   <p>шаг 1 из 3</p>
   </div>
';
  
   
$result = mysql_query("SELECT * FROM cart,products WHERE cart.cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND products.id = cart.cart_id_product",$link);

If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);

   echo '  
   <div id="header-list-cart">    
   <div id="head1" >Изображение</div>
   <div id="head2" >Наименование товара</div>
   <div id="head3" >Кол-во</div>
   <div id="head4" >Цена</div>
   <div id="head5" ><a href="cart.php?action=clear" >Очистить</a></div>
   </div> 
   ';

do
{

$int = $row["cart_price"] * $row["cart_count"];
$all_price = $all_price + $int;

if  (strlen($row["image"]) > 0 /*&& file_exists("./uploads_images/".$row["image"])*/)
{
$img_path = './upload_images/'.$row[image];
$width = 125;
$height = 125;
} else {
$img_path = "../images/noimage.jpg";
$width = 125;
$height = 125;
}

echo '

<div class="block-list-cart">

<div class="img-cart">
<p align="center"><img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" /></p>
</div>

<div class="title-cart">
<p><a href="">'.$row["title"].'</a></p>
<p class="cart-mini-features">

</p>
</div>

<div class="count-cart">
<ul class="input-count-style">

<li>
<p align="center" iid="'.$row["cart_id"].'" class="count-minus">-</p>
</li>

<li>
<p align="center"><input id="input-id'.$row["cart_id"].'" iid="'.$row["cart_id"].'" class="count-input" maxlength="3" type="text" value="'.$row["cart_count"].'" /></p>
</li>

<li>
<p align="center" iid="'.$row["cart_id"].'" class="count-plus">+</p>
</li>

</ul>
</div>

<div align="center" id="tovar'.$row["cart_id"].'" class="price-product"><h5><span class="span-count" >'.$row["cart_count"].'</span> x <span>'.$row["cart_price"].'</span></h5><p price="'.$row["cart_price"].'" >'.group_numerals($int).' руб</p></div>
<div class="delete-cart" align="center"><a  href="cart.php?id='.$row["cart_id"].'&action=delete" ><img src="/images/bsk_item_del.png" /></a></div>

<div id="bottom-cart-line"></div>
</div>


';

    
}
 while ($row = mysql_fetch_array($result));
 
 echo '
 <h3 class="itog-price" align="right">Итого: <strong>'.group_numerals($all_price).'</strong> руб</h3>
 <p align="right" class="button-next" ><a href="cart.php?action=confirm" >Далее</a></p> 
 ';
  
} 
else
{
    echo '<h3 id="clear-cart" align="center">Корзина пуста</h3>';
}
        break;		
        
}
	
?>

 </section>
 <?php include("include/footer.php"); ?>
 </div>
 </body>
</html>