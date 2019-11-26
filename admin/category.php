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

  $_SESSION['urlpage'] = "<a href='index.php' >Главная</a> \ <a href='category.php' >Категории</a>";
  
  include("../include/dbconn.php");
  include('../functions/sec.php');   
  
  if ($_POST["submit_cat"]) {
  if ($_SESSION['add_category'] == '1') {

    $error = array();
    
  if (!$_POST["cat_name"])  $error[] = "Укажите категорию!"; 
  if (!$_POST["cat_url"])  $error[] = "Укажите URL категории!"; 
  
  if (count($error)) {
      $_SESSION['message'] = "<p id='form-error'>".implode('<br />',$error)."</p>"; 
  } else {
     $cat_url = clear_string($_POST["cat_url"]);
	 $cat_name = clear_string($_POST["cat_name"]);
	 
                    mysql_query("INSERT INTO categorys(cat_url,cat_name)
						VALUES(						
                            '".$cat_url."',
							'".$cat_name."'
 					)",$link);
					
	  $id = mysql_insert_id();
      if (empty($_POST["upload_image"]))
      {        
      include("actions/upload-imagecat.php");
      unset($_POST["upload_image"]);           
      } 
                   
     $_SESSION['message'] = "<p id='form-success'>Категория успешно добавлена!</p>";   
  }
 } else {
  $msgerror = 'У вас нет прав на добавление категорий!';  
}  
}
if ($_POST["submit_subcat"]) {
if ($_SESSION['add_category'] == '1') {

    $error = array();
    
  if (!$_POST["subcat_name"])  $error[] = "Укажите подкатегорию!"; 
  if (!$_POST["subcat_url"]) $error[] = "Укажите URL подкатегории!";
  
  if (count($error)) {
      $_SESSION['message'] = "<p id='form-error'>".implode('<br />',$error)."</p>"; 
  } else {
     $subcat_name = clear_string($_POST["subcat_name"]);
     $subcat_url = clear_string($_POST["subcat_url"]);
	 $cat_id = clear_string($_POST["cat_select"]);
	 
    
                    mysql_query("INSERT INTO subcategorys(subcat_url,subcat_name,cat_id)
						VALUES(						
                            '".$subcat_url."',
							'".$subcat_name."',
                            '".$cat_id."'                              
						)",$link);
                   
     $_SESSION['message'] = "<p id='form-success'>Подкатегория успешно добавлена!</p>";   
  }
    
} else {
  $msgerror = 'У вас нет прав на добавление подкатегорий!';  
}  
}
if ($_POST["submit_del"]) {
if ($_SESSION['delete_category'] == '1') {
    $error = array();
    
  if (!$_POST["cat_type"])  $error[] = "Укажите подкатегорию!"; 

  if (count($error)) {
      $_SESSION['message'] = "<p id='form-error'>".implode('<br />',$error)."</p>"; 
  } else {
	 $subcat_id = clear_string($_POST["cat_type"]);
	 
    
                    mysql_query("DELETE FROM subcategorys WHERE id=$subcat_id",$link);
                   
     $_SESSION['message'] = "<p id='form-success'>Подкатегория успешно удалена!</p>";   
  }
    
} else {
  $msgerror = 'У вас нет прав на добавление подкатегорий!';  
}  
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" /> 
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script> 
    <script type="text/javascript" src="js/script.js"></script> 
	<title>Панель Управления - Категории</title>
</head>
<body>
<div id="block-body">
<?php
	include("include/block-header.php");
?>
<div id="block-content">
<div id="block-parameters">
<p id="title-page" >Категории</p>
</div>
<?php
if (isset($msgerror)) echo '<p id="form-error" align="center">'.$msgerror.'</p>';

		if(isset($_SESSION['message']))
		{
		echo $_SESSION['message'];
		unset($_SESSION['message']);
		}
?>

<ul id="cat_products">
<li>
<label>Категории</label>
<form method="post">
<select name="cat_type" id="cat_type" size="10">

<?php
$result1 = mysql_query("SELECT a.cat_name,a.cat_url,b.id,b.subcat_name,b.subcat_url FROM categorys AS a LEFT JOIN subcategorys AS b ON a.id = b.cat_id;",$link);
 
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
<?php
	if ($_SESSION['delete_category'] == '1')
    {
       echo '<li><input type="submit" name="submit_del" id="submit_form" value="Удалить"/></li>';  
    }
?>
</li>
</form>
<form method="post">
<h3 class="h3click">Добавить категорию</h3>
<li>
<label>Латиница (URL)</label>
<input type="text" name="cat_url" />
</li>
<li>
<label>Название</label>
<input type="text" name="cat_name" />
</li>
<label>Картинка</label>
<input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
<input type="file" name="upload_image" />
<li>
<input type="submit" name="submit_cat" id="submit_form" />
</li>
</form>
<form method="post">
<h3 class="h3click">Добавить подкатегорию</h3>
<li>
<label>Категория</label>
<select name="cat_select" id="cat_select">
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
<label>Латиница (URL)</label>
<input type="text" name="subcat_url" />
</li>
<li>
<label>Название</label>
<input type="text" name="subcat_name" />
</li>
<li>
<input type="submit" name="submit_subcat" id="submit_form" />
</li>
</ul>
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