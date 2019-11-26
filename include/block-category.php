 <ul class="tlist">
<?php 
defined('shoppromoauto') or die ('Загрузка...');

//Выборка товаров из БД
$result = mysql_query("SELECT * FROM categorys",$link);
if (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
do {


$img_path = './upload_images/'.$row[cat_img];
$width = 70;
$height = 70;

echo '
<li class="cat_item">
      	<a href="../view_cat.php?cat='.$row[id].'">
          <span class="cat_photo">
            <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" alt="'.$row[cat_name].'">
          </span>
          <div id="cat_title"><b>'.$row[cat_name].'</b></div>
		  </a>
 </li>';
} while ($row = mysql_fetch_array($result));
}
echo '</ul>';
?>

<br><br>