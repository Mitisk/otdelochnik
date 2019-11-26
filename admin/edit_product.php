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

  $_SESSION['urlpage'] = "<a href='index.php' >Главная</a> \ <a href='tovar.php' >Товары</a> \ <a>Изменение товара</a>";
  
  include("../include/dbconn.php");
  include('../functions/sec.php');
  
  $id = clear_string($_GET["id"]);
  $action = clear_string($_GET["action"]);
if (isset($action))
{
   switch ($action) {

	    case 'delete':
     
    if ($_SESSION['edit_tovar'] == '1')
    {
         
         if (file_exists("../upload_images/".$_GET["img"]))
        {
          unlink("../upload_images/".$_GET["img"]);  
        }
            
    }else
    {
       $msgerror = 'У вас нет прав на изменение товара!'; 
    }     
    
	    break;

	} 
}
    if ($_POST["submit_save"])
    {
    if ($_SESSION['edit_tovar'] == '1')
    { 
      $error = array();
    
    // Проверка полей
        
       if (!$_POST["form_title"])
      {
         $error[] = "Укажите название товара";
      }
      
       if (!$_POST["form_price"])
      {
         $error[] = "Укажите цену";
      }
          
       if (!$_POST["form_category"])
      {
         $error[] = "Укажите категорию";         
      }else
      {
       	$result = mysql_query("SELECT * FROM categorys WHERE id='{$_POST["form_category"]}'",$link);
        $row = mysql_fetch_array($result);
        $selectbrand = $row["type"];
      }
 
 
      if (empty($_POST["upload_image"]))
      {        
      include("actions/upload-image.php");
      unset($_POST["upload_image"]);           
      } 
      
     
 // Проверка чекбоксов
      
       if ($_POST["chk_visible"])
       {
          $chk_visible = "1";
       }else { $chk_visible = "0"; }
      
       if ($_POST["chk_new"])
       {
          $chk_new = "1";
       }else { $chk_new = "0"; }
      
       if ($_POST["chk_sale"])
       {
          $chk_sale = "1";
       }else { $chk_sale = "0"; }                   
      
                                      
       if (count($error))
       {           
            $_SESSION['message'] = "<p id='form-error'>".implode('<br />',$error)."</p>";
            
       }else
       {
                           
       $querynew = "title='{$_POST["form_title"]}',price='{$_POST["form_price"]}',nprice='{$_POST["form_price_old"]}',zacup_price='{$_POST["form_price_zacup"]}',description='{$_POST["txt1"]}',features='{$_POST["txt2"]}',category='{$_POST["form_type"]}',subcategory='{$_POST["form_category"]}',new='$chk_new',sale='$chk_sale',visible='$chk_visible'"; 
           
       $update = mysql_query("UPDATE products SET $querynew WHERE id = '$id'",$link); 
                   
      $_SESSION['message'] = "<p id='form-success'>Товар успешно изменен!</p>";
                
}
}
else
    {
       $msgerror = 'У вас нет прав на изменение товара!'; 
    }             
}   

?>
<html>
 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="jquery_confirm/jquery_confirm.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script> 
    <script type="text/javascript" src="js/script.js"></script>   
    <script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js"></script>
	<title>Панель Управления</title>
</head>
<body>
<div id="block-body">
<?php
	include("include/block-header.php");
?>
<div id="block-content">
<div id="block-parameters">
<p id="title-page" >Редактирование товара</p>
</div>
<?php
if (isset($msgerror)) echo '<p id="form-error" align="center">'.$msgerror.'</p>';

		 if(isset($_SESSION['message']))
		{
		echo $_SESSION['message'];
		unset($_SESSION['message']);
		}
        
     if(isset($_SESSION['answer']))
		{
		echo $_SESSION['answer'];
		unset($_SESSION['answer']);
		} 
?>
<?php
	$result = mysql_query("SELECT * FROM products WHERE id='$id'",$link);
    
If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
do
{
    
echo '

<form enctype="multipart/form-data" method="post">
<ul id="edit-tovar">

<li>
<label>Название товара</label>
<input type="text" name="form_title" value="'.$row["title"].'" />
</li>

<li>
<label>Закупочная цена</label>
<input type="text" name="form_price_zacup" value="'.$row["zacup_price"].'" />
</li>

<li>
<label>Цена</label>
<input type="text" name="form_price" value="'.$row["price"].'"  />
</li>

<li>
<label>Старая цена</label>
<input type="text" name="form_price_old" value="'.$row["nprice"].'"  />
</li>

<li>
<label>Категория</label>
<select name="form_type" id="type" size="1" >
';

$result = mysql_query("SELECT * FROM categorys ORDER BY cat_name ASC",$link);
 
If (mysql_num_rows($result) > 0) {
$row = mysql_fetch_array($result);
do {
	echo '
       <option value="'.$row["id"].'" >'.$row["cat_name"].' ('.$row["cat_url"].')</option>
    ';
} while ($row = mysql_fetch_array($result));
}    

echo '
</select>
</li>

<li>
<label>Подкатегория</label>
<select name="form_category" size="10" >
';
$result1 = mysql_query("SELECT a.cat_name,a.cat_url,b.id,b.subcat_name,b.subcat_url FROM categorys AS a LEFT JOIN subcategorys AS b ON a.id = b.cat_id ORDER BY a.cat_name ASC;",$link);
 
If (mysql_num_rows($result1) > 0) {
$cat = mysql_fetch_array($result1);
do
{
	echo '
    
       <option value="'.$cat["id"].'" >'.$cat["cat_name"].' ('.$cat["cat_url"].') - '.$cat["subcat_name"].' ('.$cat["subcat_url"].')</option>
    
    ';
} while ($cat = mysql_fetch_array($result1));
}    

echo '
</select>
</ul> 
';
}while ($row = mysql_fetch_array($result));
}

$result = mysql_query("SELECT * FROM products WHERE id='$id'",$link);
    
If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
do
{
   if  (strlen($row["image"]) > 0 && file_exists("../upload_images/".$row["image"]))
{
$img_path = '../upload_images/'.$row["image"];
$width = 100; 
$height = 100; 

echo '
<label class="stylelabel" >Основная картинка</label>
<div id="baseimg">
<img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" />
<a href="edit_product.php?id='.$row["id"].'&img='.$row["image"].'&action=delete" ></a>
</div>

';
   
}else
{  
echo '
<label class="stylelabel" >Основная картинка</label>

<div id="baseimg-upload">
<input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
<input type="file" name="upload_image" />

</div>
';
}

echo '
<h3 class="h3click" >Описание товара</h3>
<div class="div-editor2" >
<textarea id="editor1" name="txt1" cols="100" rows="20">'.$row["description"].'</textarea>
		<script type="text/javascript">
			var ckeditor1 = CKEDITOR.replace( "editor1" );
			AjexFileManager.init({
				returnTo: "ckeditor",
				editor: ckeditor1
			});
		</script>
 </div>          

<h3 class="h3click" >Характеристики</h3>
<div class="div-editor4" >
<textarea id="editor2" name="txt2" cols="100" rows="20">'.$row["features"].'</textarea>
		<script type="text/javascript">
			var ckeditor1 = CKEDITOR.replace( "editor2" );
			AjexFileManager.init({
				returnTo: "ckeditor",
				editor: ckeditor1
			});
		</script>
  </div> 
';

if ($row["visible"] == '1') $checked1 = "checked";
if ($row["new"] == '1') $checked2 = "checked";
if ($row["sale"] == '1') $checked4 = "checked";
 

echo ' 
<h3 class="h3title" >Настройки товара</h3>   
<ul id="chkbox">
<li><input type="checkbox" name="chk_visible" id="chk_visible" '.$checked1.' /><label for="chk_visible" >Показывать товар</label></li>
<li><input type="checkbox" name="chk_new" id="chk_new" '.$checked2.' /><label for="chk_new" >Новый товар</label></li>
<li><input type="checkbox" name="chk_sale" id="chk_sale" '.$checked4.' /><label for="chk_sale" >Товар со скидкой</label></li>
</ul> 


    <p align="right" ><input type="submit" id="submit_form" name="submit_save" value="Сохранить"/></p>     
</form>
';

}while ($row = mysql_fetch_array($result));
}
?> 




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