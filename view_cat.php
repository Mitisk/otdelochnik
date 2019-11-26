<?php 
 session_start();
ini_set('display_errors', 'Off');
 define('shoppromoauto', true);
include("include/dbconn.php");
include("functions/sec.php");
include("include/auth_cookie.php");
$cat = clear_string($_GET['cat']); 
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
 

  
<?php 
$sql_select = "SELECT * FROM categorys WHERE id='$cat'";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);
if($row) {
echo  '
<div id="breadcrumbs"><div id="breadcrumbs-tree"><p id="nav-breadcrumbs"><a href="../index.php">Главная</a> / <span>'.$row[cat_name].'</span></p></div></div><br><br><br><h2>'.$row[cat_name].'</h2>
';}?>


 <ul class="tlist">
<?php 

//Выборка товаров из БД
$result = mysql_query("SELECT * FROM subcategorys WHERE cat_id ='$cat' ",$link);
if (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
do {

$img_path = './upload_images/'.$row[subcat_img];
$width = 120;
$height = 120;

echo '
<li class="subcat_item">
      	<a href="../view_products.php?cat='.$cat.'&subcat='.$row[id].'">
          <span class="subcat_photo" align="center">
            <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" alt="'.$row[subcat_name].'">
          </span>
          <div id="subcat_title" align="center"><b>'.$row[subcat_name].'</b></div>
		  </a>
 </li>';
} while ($row = mysql_fetch_array($result));
} else {
echo'<H3 align="center">Категория недоступна</H3>';
}

echo '</ul><div style="clear: both;"></div>';

 ?>

 <br> <br> <br> 
 
 
 </section>
 <?php include("include/footer.php"); ?>
 </div>
 </body>
</html>