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

  $_SESSION['urlpage'] = "<a href='index.php' >Главная</a> \ <a href='tovar.php' >Товары</a> \ <a>Добавление товара</a>";
  
  include("../include/dbconn.php");
  include('../functions/sec.php');

    if ($_POST["submit_add"])
    {
 if ($_SESSION['add_tovar'] == '1')
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
            
       } else {


                           
              		mysql_query("INSERT INTO products (title,price,nprice,zacup_price,description,features,category,subcategory,datetime,new,sale,visible)
						VALUES(						
                            '".$_POST["form_title"]."',
                            '".$_POST["form_price"]."',
							'".$_POST["form_price_old"]."',
							'".$_POST["form_price_zacup"]."',
							'".$_POST["txt1"]."',
							'".$_POST["txt2"]."',
							'".$_POST["form_type"]."',
							'".$_POST["form_category"]."',
							NOW(),
                            '".$chk_new."',
                            '".$chk_sale."',
                            '".$chk_visible."'
						)",$link);
                   
      $_SESSION['message'] = "<p id='form-success'>Товар успешно добавлен!</p>";
      $id = mysql_insert_id();
                 
      if (empty($_POST["upload_image"]))
      {        
      include("actions/upload-image.php");
      unset($_POST["upload_image"]);           
      } 
      
}

    
 } else {
   $msgerror = 'У вас нет прав на добавление товаров!'; 
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
<p id="title-page" >Добавление товара</p>
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

<form enctype="multipart/form-data" method="post">
<ul id="edit-tovar">

<li>
<label>Название товара</label>
<input type="text" name="form_title" />
</li>

<li>
<label>Закупочная цена</label>
<input type="text" name="form_price_zacup"  />
</li>

<li>
<label>Старая цена</label>
<input type="text" name="form_price_old"  />
</li>

<li>
<label>Цена</label>
<input type="text" name="form_price"  />
</li>

<li>
<label>Категория</label>

<select name="form_type" id="type" size="1" >

<?php
$result = mysql_query("SELECT * FROM categorys ORDER BY cat_name ASC",$link);
 
If (mysql_num_rows($result) > 0) {
$row = mysql_fetch_array($result);
do {
	echo '
       <option value="'.$row["id"].'" >'.$row["cat_name"].' ('.$row["cat_url"].')</option>
    ';
} while ($row = mysql_fetch_array($result));
}    
?>

</select>
</li>

<li>
<label>Подкатегории</label>
<select name="form_category" size="10" multiple>

<?php
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
?>

</select>
</ul> 
<label class="stylelabel" >Картинка</label>

<div id="baseimg-upload">
<input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
<input type="file" name="upload_image" />

</div>

<h3 class="h3click" >Описание товара</h3>
<div class="div-editor1" >
<textarea id="editor1" name="txt1" cols="100" rows="10"></textarea>
		<script type="text/javascript">
			var ckeditor1 = CKEDITOR.replace( "editor1" );
			AjexFileManager.init({
				returnTo: "ckeditor",
				editor: ckeditor1
			});
		</script>
 </div>          

<h3 class="h3click" >Характеристики</h3>
<div class="div-editor2" >
<textarea id="editor2" name="txt2" cols="100" rows="10"></textarea>
		<script type="text/javascript">
			var ckeditor1 = CKEDITOR.replace( "editor2" );
			AjexFileManager.init({
				returnTo: "ckeditor",
				editor: ckeditor1
			});
		</script>
  </div> 

     
<h3 class="h3title" >Настройки товара</h3>   
<ul id="chkbox">
<li><input type="checkbox" name="chk_visible" id="chk_visible" /><label for="chk_visible" >Показывать товар</label></li>
<li><input type="checkbox" name="chk_new" id="chk_new"  /><label for="chk_new" >Новый товар</label></li>
<li><input type="checkbox" name="chk_sale" id="chk_sale"  /><label for="chk_sale" >Товар со скидкой</label></li>
</ul> 


    <p align="right" ><input type="submit" id="submit_form" name="submit_add" value="Добавить товар"/></p>     
</form>


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