<a name="shop">&nbsp;</a><h2>Магазин</h2>

<div class="box">

<ul class="tabs">
<li class="<?php echo $sort_class5; ?>"><a id="selectsort" href="../index.php#shop" >Без сортировки</a></li>
<li class="<?php echo $sort_class1; ?>"><a id="selectsort" href="../index.php?sort=price-asc#shop" >Сначала дешевые</a></li>
<li class="<?php echo $sort_class2; ?>"><a id="selectsort" href="../index.php?sort=price-desc#shop" >Сначала дорогие</a></li>
<li class="<?php echo $sort_class3; ?>"><a id="selectsort" href="../index.php?sort=new#shop" >Новинки</a></li>
<li class="<?php echo $sort_class4; ?>"><a id="selectsort" href="../index.php?sort=sale#shop" >Скидки</a></li>

</ul>
</div>


 <ul class="tlist">
<?php 
defined('shoppromoauto') or die ('Загрузка...');
include('functions/cartprice.php');
//Постраничная навигация
$num = 4;
$page = (int)$_GET['page'];
$count = mysql_query("SELECT COUNT(*) FROM products WHERE visible ='1'",$link);
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
$result = mysql_query("SELECT * FROM products WHERE visible ='1' ORDER BY $sorting $query_start_num",$link);
if (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
do {

//Преобразование картинок
if ($row[image] != "" && file_exists("./upload_images/".$row[image])) {
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
}
echo '</ul>';
//Ссылки навигации
if ($page != 1) $pstr_prev = '<li><a href="../index.php?page='.($page - 1).'#shop" class="page">&lt;</a></li>';
if ($page != $total) $pstr_next = '<li><a href="../index.php?page='.($page + 1).'#shop" class="page">&gt;</a></li>';
//По страницам
if ($page - 5 > 0) $page5left = '<li><a href="../index.php?page='.($page - 5).'#shop" class="page">'.($page - 5).'</a></li>';
if ($page - 4 > 0) $page4left = '<li><a href="../index.php?page='.($page - 4).'#shop" class="page">'.($page - 4).'</a></li>';
if ($page - 3 > 0) $page3left = '<li><a href="../index.php?page='.($page - 3).'#shop" class="page">'.($page - 3).'</a></li>';
if ($page - 2 > 0) $page2left = '<li><a href="../index.php?page='.($page - 2).'#shop" class="page">'.($page - 2).'</a></li>';
if ($page - 1 > 0) $page1left = '<li><a href="../index.php?page='.($page - 1).'#shop" class="page">'.($page - 1).'</a></li>';
if ($page + 5 <= $total) $page5right = '<li><a href="../index.php?page='.($page + 5).'#shop" class="page">'.($page + 5).'</a></li>';
if ($page + 4 <= $total) $page4right = '<li><a href="../index.php?page='.($page + 4).'#shop" class="page">'.($page + 4).'</a></li>';
if ($page + 3 <= $total) $page3right = '<li><a href="../index.php?page='.($page + 3).'#shop" class="page">'.($page + 3).'</a></li>';
if ($page + 2 <= $total) $page2right = '<li><a href="../index.php?page='.($page + 2).'#shop" class="page">'.($page + 2).'</a></li>';
if ($page + 1 <= $total) $page1right = '<li><a href="../index.php?page='.($page + 1).'#shop" class="page">'.($page + 1).'</a></li>';

if ($page + 5 < $total) {
$strtotal = '<li><a href="../index.php?page='.($page + 6).'#shop" class="page">...</a></li><li><a href="../index.php?page='.$total.'#shop" class="page">'.$total.'</a></li>';
} else {
$strtotal = "";
}
if ($total > 1) {
echo '
<div class="navigation">
<ul>';
echo $pstr_prev.$page5left.$page4left.$page3left.$page2left.$page1left."<li><a href='../index.php?page=".($page)."#shop' class='pageon'>".($page)."</a></li>".$page1right.$page2right.$page3right.$page4right.$page5right.$strtotal.$pstr_next;
echo '
</ul>
</div>
';
}

 ?>

 <br> <br> <br> 
 
 


