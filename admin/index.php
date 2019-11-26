<?php
session_start();
if ($_SESSION['auth_admin'] == "yes_auth")
{
	define('shoppromoauto', true);
       
       if (isset($_GET["logout"]))
    {
        unset($_SESSION['auth_admin']);
        header("Location: login.php");
    }

  $_SESSION['urlpage'] = "<a href='index.php' >Главная</a>";
  
  include("../include/dbconn.php");

?>
<html>
 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
	<title>Панель Управления</title>
</head>
<body>
<div id="block-body">
<?php
include("include/block-header.php");
    
     // Общее количество заказов 
 $query1 = mysql_query("SELECT * FROM orders",$link);
 $result1 = mysql_num_rows($query1);
 // Общее количество товаров 
 $query2 = mysql_query("SELECT * FROM products",$link);
 $result2 = mysql_num_rows($query2);   
  // Общее количество клиентов 
 $query4 = mysql_query("SELECT * FROM users",$link);
 $result4 = mysql_num_rows($query4);
?>
<div id="block-content">
<div id="block-parameters">
<p id="title-page" >Общая статистика</p>
</div>

<ul id="general-statistics">
<li><p>Всего заказов - <span><?php echo $result1; ?></span></p></li>
<li><p>Товаров - <span><?php echo $result2; ?></span></p></li>
<li><p>Клиенты - <span><?php echo $result4; ?></span></p></li>
</ul>

<h3 id="title-statistics">Статистика продаж</h3>

<TABLE align="center" CELLPADDING="10" WIDTH="100%">
<TR>
<TH>Дата</TH>
<TH>Товар</TH>
<TH>Цена</TH>
<TH>Статус</TH>
</TR>


<?php

$result = mysql_query("SELECT * FROM orders,buy_products WHERE orders.order_pay='accepted' AND orders.order_id=buy_products.buy_id_order",$link);
 
 If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
do
{

 $result2 = mysql_query("SELECT * FROM products WHERE id='{$row["buy_id_product"]}'",$link);   
  If (mysql_num_rows($result2) > 0)
{
 $row2 = mysql_fetch_array($result2);
}
    
$statuspay = "";
if ($row["order_pay"] == "accepted") $statuspay = "Оплачено";

echo '
 <TR>
<TD  align="CENTER" >'.$row["order_datetime"].'</TD>
<TD  align="CENTER" >'.$row2["title"].'</TD>
<TD  align="CENTER" >'.$row2["price"].'</TD>
<TD  align="CENTER" >'.$statuspay.'</TD>
</TR>
';

	}
     while ($row = mysql_fetch_array($result));
}     
?>

</TABLE>
</div>
</div>
</body>
</html>
<?php
}else
{
    header("Location: login.php");
}
?>