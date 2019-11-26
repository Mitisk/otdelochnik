<?php
 session_start();
 define('shoppromoauto', true);
 ini_set('display_errors', 'Off');
 include("include/dbconn.php");
 include('functions/sec.php');
 include('functions/cartprice.php');
 include("include/auth_cookie.php");
 $priceforfreedelivery = 1000;
   $id = clear_string($_GET["id"]); 

     $seoquery = mysql_query("SELECT title,description FROM products WHERE id='$id' AND visible='1'",$link);
     
     If (mysql_num_rows($seoquery) > 0)
     {
        $resquery = mysql_fetch_array($seoquery);
     }   
 ?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="css/style.css" type="text/css" />
	 <link rel="stylesheet" href="css/reset.css" type="text/css" />
     <link rel="stylesheet" href="css/modal.css" type="text/css" />
	 <link rel="stylesheet" href="css/product.css" type="text/css" />
     <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
     <script type="text/javascript" src='../js/jquery-1.8.2.min.js'></script>
  <title>Промоавто.рф</title>
 </head>
 <body>
 <div id="wrapper">
 <?php include("include/header.php"); ?>
 <?php include("include/slider.php"); ?>
 <section class="content normal">

<?php
$sql_select = "SELECT id, category, subcategory FROM products WHERE products.id='$id'";
$result = mysql_query($sql_select);
$categorys = mysql_fetch_array($result);
if($categorys) {
$tcategory = $categorys[category];
$tsubcategory = $categorys[subcategory];
}
$sql_select = "SELECT id, cat_name FROM categorys WHERE id='$tcategory'";
$result = mysql_query($sql_select);
$categorys = mysql_fetch_array($result);
if($categorys) {
$idcategory = $categorys[id];
$category = $categorys[cat_name];
}
$sql_select = "SELECT id, subcat_name FROM subcategorys WHERE id='$tsubcategory'";
$result = mysql_query($sql_select);
$categorys = mysql_fetch_array($result);
if($categorys) {
$idsubcategory = $categorys[id];
$subcategory = $categorys[subcat_name];
}

$result1 = mysql_query("SELECT * FROM products WHERE id='$id' AND visible='1'",$link);
If (mysql_num_rows($result1) > 0)
{
$row1 = mysql_fetch_array($result1);
do
{   
if  (strlen($row1["image"]) > 0 /*&& file_exists("../upload_images/".$row1["image"])*/)
{
$img_path = '../upload_images/'.$row1["image"];
$width = 300; 
$height = 300;  
}else
{
$img_path = "../images/noimage.jpg";
$width = 300;
$height = 300;
}     

echo  '

<div id="breadcrumbs">
<div id="breadcrumbs-tree">
<p id="nav-breadcrumbs"><a href="../index.php">Главная</a> / <a href="../view_cat.php?cat='.$idcategory.'">'.$category.'</a> / <a href="../view_products.php?cat='.$idcategory.'&subcat='.$idsubcategory.'">'.$subcategory.'</a></p></div>
<script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
<script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
<div id="share" class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,twitter,whatsapp"></div>
</div>


<div id="content-info">

<img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" />

<div id="block-description">
<h1><strong>'.$row1["title"].'</strong></h1>
<div id="price-and-cart">
<div id="style-price">
'; if  ($row1["nprice"] > 0) {echo '<p id="style-price-old" >'.group_numerals($row1["nprice"]).' руб.</p>';} else {echo '<div id="probel"></div>';};
echo '<p id="style-price-new" >'.group_numerals($row1["price"]).' руб.</p>
</div>
<div id="add-cart">
<a onclick="SendToCart('.$row1["id"].');" class="add-cart">В корзину</a>
</div></div>
<div id="advantage">
'; 
if  (strlen($row1["new"]) == 1) echo '<img src="../images/new.png" width="50px" title="Это новый товар" style="margin: 6px;" />'; 
if  ($row1["nprice"] > 0) echo '<img src="../images/sale.png" width="50px" title="На этот товар предоставляется скидка" style="margin: 6px;" />'; 
if  ($row1["price"] > $priceforfreedelivery) echo '<img src="../images/transport.png" width="50px" title="Бесплатная доставка" style="margin: 6px;" />'; 
echo '
</div>
<div id="content-text">'.$row1["description"].'</div>
<div id="content-features">'.$row1["features"].'</div>

</div>
<div style="clear: both;"></div>
</div>

';

   
} while ($row1 = mysql_fetch_array($result1));

} else { echo '<center><H3>Нет такого товара<H3></center>';}
	
?>
 
 </section>
 <?php include("include/footer.php"); ?>
 </div>
 </body>
</html>