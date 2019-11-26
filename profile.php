<?php
session_start();
define('shoppromoauto', true);
ini_set('display_errors', 'Off');
if ($_SESSION['auth'] == 'yes_auth')
{	
   include("include/dbconn.php");
   include("functions/sec.php");
 
   if ($_POST["save_submit"])
     {
        
    $_POST["info_surname"] = clear_string($_POST["info_surname"]);
    $_POST["info_name"] = clear_string($_POST["info_name"]);
    $_POST["info_patronymic"] = clear_string($_POST["info_patronymic"]);
    $_POST["info_address"] = clear_string($_POST["info_address"]);
    $_POST["info_phone"] = clear_string($_POST["info_phone"]);
    $_POST["info_email"] = clear_string($_POST["info_email"]);     
              
    $error = array();
	
    $pass   = md5($_POST["info_pass"]);
    $pass   = strrev($pass);
    $pass   = "7f6see".$pass."4t9";
    
	if($_SESSION['auth_pass'] != $pass)
	{
		$error[]='Неверный текущий пароль!';
	}else
    {
        
      if($_POST["info_new_pass"] != "")
	{
		        if(strlen($_POST["info_new_pass"]) < 7 || strlen($_POST["info_new_pass"]) > 15)
            	{
		           $error[]='Укажите новый пароль от 7 до 15 символов!';
	            }else
                {
                     $newpass   = md5(clear_string($_POST["info_new_pass"]));
                     $newpass   = strrev($newpass);
                     $newpass   = "7f6see".$pass."4t9";
                     $newpassquery = "pass='".$newpass."',";
                }
	}
    
    
    
        if(strlen($_POST["info_surname"]) < 3 || strlen($_POST["info_surname"]) > 15)
	{
		$error[]='Укажите Фамилию от 3 до 15 символов!';
	}
    
    
        if(strlen($_POST["info_name"]) < 3 || strlen($_POST["info_name"]) > 15)
	{
		$error[]='Укажите Имя от 3 до 15 символов!';
	}
    
    
        /*if(strlen($_POST["info_patronymic"]) < 3 || strlen($_POST["info_patronymic"]) > 25)
	{
		$error[]='Укажите Отчество от 3 до 25 символов!';
	}  
    */
    
        if(!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i",trim($_POST["info_email"])))
	{
		$error[]='Укажите корректный email!';
	}
    
      /*if(strlen($_POST["info_phone"]) == "")
	{
		$error[]='Укажите номер телефона!';
	} */
    
      /*if(strlen($_POST["info_address"]) == "")
	{
		$error[]='Укажите адрес доставки!';
	}    */  
    
    
        
    }
    
  if(count($error))
	{
		$_SESSION['msg'] = "<p align='left' style=\"color: red;\">".implode('<br />',$error)."</p>";
	} else {
        $_SESSION['msg'] = "<p align='left' style=\"color: green;\">Данные успешно сохранены!</p>";
           
     $dataquery = $newpassquery."surname='".$_POST["info_surname"]."',patronymic='".$_POST["info_patronymic"]."',name='".$_POST["info_name"]."',mail='".$_POST["info_email"]."',phone='".$_POST["info_phone"]."',address='".$_POST["info_address"]."'";      
     $update = mysql_query("UPDATE users SET $dataquery WHERE login = '{$_SESSION['auth_login']}'",$link);
      
    if ($newpass){ $_SESSION['auth_pass'] = $newpass; } 
    
    
    $_SESSION['auth_surname'] = $_POST["info_surname"];
    $_SESSION['auth_name'] = $_POST["info_name"];
    $_SESSION['auth_patronymic'] = $_POST["info_patronymic"];
    $_SESSION['auth_address'] = $_POST["info_address"];
    $_SESSION['auth_phone'] = $_POST["info_phone"];
    $_SESSION['auth_email'] = $_POST["info_email"];    
        
    }
        
     }  
   
?>
<html>
 <head>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/profile.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="css/cart.css" type="text/css" />

    
    <script type="text/javascript" src="/js/jquery-1.8.2.min.js"></script> 
    
	<title>Промоавто.рф</title>
</head>
<body>
<div id="wrapper">
 <?php include("include/header.php"); ?>
 <?php include("include/slider.php"); ?>
 <section class="content normal">
<h2>Профиль пользователя</h2>


<div id="pageleft">

<form method="post">
<h3 class="title-h3" >Мои данные: 
<?php
	if($_SESSION['msg']) {
		echo $_SESSION['msg'];
		unset($_SESSION['msg']);
	}
?>
</H3>
<ul id="info">
<li>
<label for="info_pass">Текущий пароль</label>
<span class="star">*</span>
<input type="text" name="info_pass" id="info_pass" value="" />
</li>

<li>
<label for="info_new_pass">Новый пароль</label>
<span class="star">*</span>
<input type="text" name="info_new_pass" id="info_new_pass" value="" />
</li>

<li>
<label for="info_surname">Фамилия</label>
<span class="star">*</span>
<input type="text" name="info_surname" id="info_surname" value="<?php echo $_SESSION['auth_surname']; ?>"  />
</li>

<li>
<label for="info_name">Имя</label>
<span class="star">*</span>
<input type="text" name="info_name" id="info_name" value="<?php echo $_SESSION['auth_name']; ?>"  />
</li>

<li>
<label for="info_patronymic">Отчество</label>
<span class="star">*</span>
<input type="text" name="info_patronymic" id="info_patronymic" value="<?php echo $_SESSION['auth_patronymic']; ?>" />
</li>


<li>
<label for="info_email">E-mail</label>
<span class="star">*</span>
<input type="text" name="info_email" id="info_email" value="<?php echo $_SESSION['auth_email']; ?>" />
</li>

<li>
<label for="info_phone">Телефон</label>
<span class="star">*</span>
<input type="text" name="info_phone" id="info_phone" value="<?php echo $_SESSION['auth_phone']; ?>" />
</li>

<li>
<label for="info_address">Адрес доставки</label>
<span class="star">*</span>
<textarea name="info_address"  > <?php echo $_SESSION['auth_address']; ?> </textarea>
</li>
<p align="right"><input type="submit" id="form_submit" name="save_submit" value="Сохранить" /></p>
</form>
</ul>
</div><div id="pageright">
<?php
$id_user = $_SESSION['auth_id'];    
$all_count = mysql_query("SELECT * FROM orders WHERE (id_users='$id_user') AND (visible='yes')",$link);
$all_count_result = mysql_num_rows($all_count);
?>
<h3 class="title-h3" >Мои заказы (<? echo $all_count_result; ?>):
<?php
    if($_SESSION['msg_order'])
		{
		echo '<br>'.$_SESSION['msg_order'];
		unset($_SESSION['msg_order']);
		}
?>
</h3>
<ul id="info">
 <?php include("include/block-orders.php"); ?>
 </ul>
</div><div class="clearer"></div>

 </section>
 <?php include("include/footer.php"); ?>
 </div>

</body>
</html>
<?php
} else { header("Location: index.php");  }	
?>