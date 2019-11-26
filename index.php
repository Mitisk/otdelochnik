<?php
 session_start();
 define('shoppromoauto', true);
 ini_set('display_errors', 'Off');
 include("include/dbconn.php");
 include('functions/sec.php');
 include("include/auth_cookie.php");
 //unset ($_SESSION['auth']);

 //Для сортировки товаров через URL строку
$sorting = $_GET['sort']; 
   
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
     <script type="text/javascript" src='../js/jquery-1.8.2.min.js'></script>
  <title>Промоавто.рф</title>
 </head>
 <body>
 <div id="wrapper">
 <?php include("include/header.php"); ?>
  <?php include("include/slider.php"); ?>
 <section class="content normal">
  <?php include("include/block-category.php"); ?>
 <?php include("include/block-news.php"); ?>
 </section>
 <?php include("include/footer.php"); ?>
 </div>
 </body>
</html>