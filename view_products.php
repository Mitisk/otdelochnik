<?php 
 session_start();
 define('shoppromoauto', true);
 ini_set('display_errors', 'Off');
include("include/dbconn.php");
include("functions/sec.php");
include("include/auth_cookie.php");
include('functions/cartprice.php');
$cat = clear_string($_GET['cat']); 
$subcat = clear_string($_GET['subcat']);
 
 //Для сортировки товаров через URL строку
$sorting = clear_string($_GET['sort']); 
switch ($sorting) {
 case 'price-asc';
 $sorting = 'price ASC';
 $sort_class1 = 'active';
 break;
case 'price-desc';
 $sorting = 'price DESC';
 $sort_class2 = 'active';
 break;
case 'new';
 $sorting = 'datetime DESC';
 $sort_class3 = 'active';
 break;
case 'sale';
 $sorting = 'sale DESC';
 $sort_class4 = 'active';
 break;
 default:
 $sorting = 'id DESC';
 $sort_class5 = 'active';
 break;
}     
 
 ?>
<html>
 <head>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
     <link rel="stylesheet" href="css/style.css" type="text/css" />
	 <link rel="stylesheet" href="css/reset.css" type="text/css" />
     <link rel="stylesheet" href="css/modal.css" type="text/css" />
     <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	 <script type="text/javascript" src="js/script.js"></script> 
     <script type="text/javascript" src='../js/jquery-1.8.2.min.js'></script>
  <title>Промоавто.рф</title>
 </head>
 <body>
 <div id="wrapper">
 <?php include("include/header.php"); ?>
   <?php include("include/slider.php"); ?>
 <section class="content normal">
 
 <div id="breadcrumbs">
<div id="breadcrumbs-tree">
<?php 
$sql_select = "SELECT id, cat_name FROM categorys WHERE id='$cat'";
$result = mysql_query($sql_select);
$categorys = mysql_fetch_array($result);
if($categorys) {
$category = $categorys[cat_name];
}
$sql_select = "SELECT id, subcat_name FROM subcategorys WHERE id='$subcat'";
$result = mysql_query($sql_select);
$categorys = mysql_fetch_array($result);
if($categorys) {
$subcategory = $categorys[subcat_name];
}
echo  '
<p id="nav-breadcrumbs"><a href="../index.php">Главная</a> / <a href="../view_cat.php?cat='.$cat.'">'.$category.'</a> / <span>'.$subcategory.'</span></p></div>
';?>
</div>

<a name="shop">&nbsp;</a><h2><?php echo $subcategory;?></h2>

 <ul class="tlist">
<?php 
//Сортировка из БД
if (!empty($cat) && !empty($subcat)){
$querycat = "AND category='$cat' AND subcategory='$subcat'";
} else {
if (!empty($cat)){
$querycat = "AND category='$cat'";
} else {$querycat = "";}
}

//Постраничная навигация
$num = 8;
$page = (int)$_GET['page'];
$count = mysql_query("SELECT COUNT(*) FROM products WHERE visible ='1' $querycat",$link);
$temp = mysql_fetch_array($count);
if ($temp[0] > 0) {
$tempcount = $temp[0];
//Общее число страниц
$total = (($tempcount - 1) / $num) + 1;
$total = intval($total); //Округляем в большую сторону +1 вверху
$page = intval($page);
//Проверка
if(empty($page) or $page < 0) $page = 1;
//Если много страниц, то многоточие
if($page > $total) $page = $total;
//Вычисляем начиная с какого номераследует выводить товары
$start = $page * $num - $num;
$query_start_num = " LIMIT $start, $num";
}

//Выборка товаров из БД
$result = mysql_query("SELECT * FROM products WHERE visible ='1' $querycat ORDER BY $sorting $query_start_num",$link);
if (mysql_num_rows($result) > 0)
{

echo '
<div class="box">

<ul class="tabs">
<li class="'.$sort_class5.'"><a id="selectsort" href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'#shop" >Без сортировки</a></li>
<li class="'.$sort_class1.'"><a id="selectsort" href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&sort=price-asc#shop" >Сначала дешевые</a></li>
<li class="'.$sort_class2.'"><a id="selectsort" href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&sort=price-desc#shop" >Сначала дорогие</a></li>
<li class="'.$sort_class3.'"><a id="selectsort" href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&sort=new#shop" >Новинки</a></li>
<li class="'.$sort_class4.'"><a id="selectsort" href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&sort=sale#shop" >Скидки</a></li>

</ul>
</div>
';

$row = mysql_fetch_array($result);
do {

//Преобразование картинок
if ($row[image] != "" && file_exists("./upload_images/".$row[image]))
{
$img_path = './upload_images/'.$row[image];
$width = 200;
$height = 200;
} else {
$img_path = "../images/noimage.jpg";
$width = 200;
$height = 200;
}

echo '
<li class="shop-item">
      	<a href="../product.php?id='.$row[id].'">
          <span class="photo" style="background-image: url("/upload_images/1.jpg");">
            <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" alt="'.$row[title].'">
          </span>
          <div id="shop-title"><b>'.$row[title].'</b></div>
		  </a>
'; 
if  ($row["nprice"] > 0) {echo '<p id="shop-price-old" >'.group_numerals($row["nprice"]).' руб.</p>';};
echo '
		  <div id="shop-price"><strong>'.group_numerals($row[price]).' РУБ</strong></div>

<a onclick="SendToCart('.$row[id].');" class="knopka" id="elem" tiid="'.$row[id].'">В корзину</a>
	
 </li>
';
} while ($row = mysql_fetch_array($result));
} else {
echo'<H3 align="center">Категория недоступна</H3>';
}

echo '</ul>';
//Ссылки навигации
if ($page != 1) $pstr_prev = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page - 1).'#shop" class="page">&lt;</a></li>';
if ($page != $total) $pstr_next = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page + 1).'#shop" class="page">&gt;</a></li>';
//По страницам
if ($page - 5 > 0) $page5left = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page - 5).'#shop" class="page">'.($page - 5).'</a></li>';
if ($page - 4 > 0) $page4left = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page - 4).'#shop" class="page">'.($page - 4).'</a></li>';
if ($page - 3 > 0) $page3left = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page - 3).'#shop" class="page">'.($page - 3).'</a></li>';
if ($page - 2 > 0) $page2left = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page - 2).'#shop" class="page">'.($page - 2).'</a></li>';
if ($page - 1 > 0) $page1left = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page - 1).'#shop" class="page">'.($page - 1).'</a></li>';
if ($page + 5 <= $total) $page5right = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page + 5).'#shop" class="page">'.($page + 5).'</a></li>';
if ($page + 4 <= $total) $page4right = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page + 4).'#shop" class="page">'.($page + 4).'</a></li>';
if ($page + 3 <= $total) $page3right = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page + 3).'#shop" class="page">'.($page + 3).'</a></li>';
if ($page + 2 <= $total) $page2right = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page + 2).'#shop" class="page">'.($page + 2).'</a></li>';
if ($page + 1 <= $total) $page1right = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page + 1).'#shop" class="page">'.($page + 1).'</a></li>';

if ($page + 5 < $total) {
$strtotal = '<li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.($page + 6).'#shop" class="page">...</a></li><li><a href="../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page='.$total.'#shop" class="page">'.$total.'</a></li>';
} else {
$strtotal = "";
}
if ($total > 1) {
echo '
<div class="navigation">
<ul>';
echo $pstr_prev.$page5left.$page4left.$page3left.$page2left.$page1left."<li><a href='../view_products.php?cat='.$cat.'&subcat='.$subcat.'&page=".($page)."#shop' class='pageon'>".($page)."</a></li>".$page1right.$page2right.$page3right.$page4right.$page5right.$strtotal.$pstr_next;
echo '
</ul>
</div>
';
}

 ?>

 <br> <br> <br> 
 
 
 </section>
 <?php include("include/footer.php"); ?>
 </div>
 </body>
</html>