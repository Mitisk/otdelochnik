<?php
defined('shoppromoauto') or die ('Загрузка...');
?>
<div id="topline"></div>
<header class="header">

    	<a href="../index.php"><div class="h-logo"></div></a>
        

<script type="text/javascript">
function Remind() {
    
 var recall_email = $("#remind-email").val();
 
 if (recall_email != "" || recall_email.length < 20 ) {
    
  $.ajax({
  type: "POST",
  url: "include/remind-pass.php",
  data: "email="+recall_email,
  dataType: "html",
  cache: false,
  success: function(data) {

  if (data == 'yes')
  {
     $('#message-rem').attr("class","message-remind-success").html("Ваш пароль отправлен.").slideDown(400);
     
     $('#message-rem').html('').hide(),$('#block-remind').hide(),$('#input-email-pass').show();
 
  } else {
      $('#message-rem').attr("class","message-remind-error").html(data).slideDown(400);
   }
  }
}); 
  }
  }; 
</script>
<?php

if ($_SESSION['auth'] == 'yes_auth')
{
 
 echo '<div id="h-enter"><a href="../include/logout.php" id="logout" class="h-exit" />Выход</a><a href="../profile.php" class="h-enter-login" />Здравствуйте, '.$_SESSION['auth_name'].'!</a></div>';   
    
}else{
 
echo '<div id="h-enter"><a href="#enter" class="h-enter" />Войти</a></div>'.$_SESSION['auth']; 
echo '<!-- Модальное входа -->
<script type="text/javascript">
function SendGet() {
var auth_login = $("#auth_login").val();
var auth_pass = $("#auth_pass").val();

$.ajax({
  type: "POST",
  url: "include/auth.php",
  data: "login="+auth_login+"&pass="+auth_pass,
  dataType: "html",
  cache: false,
  success: function(data) {
  if (data == \'yes_auth\')
  {
      location.reload();
  } else {
      $("#message-auth").slideDown(400);
      $(".auth-loading").hide();
      $("#button-auth").show();
  }
  
}
});  
}
</script>
<a href="#x" class="overlay" id="enter"></a>
	<div class="popup popup-login" style="display: block;" data-from="top">
	<a href="#" class="close"></a>
  <h3>Авторизуйтесь <span>или <a href="../registration.php" class="register-lnk">Зарегистрируйтесь</a></span></h3>
  <form class="popup-auth" method="post">
  <p id="message-auth">Неверный Логин или Пароль!</p>
  	<div>
      <label class="label">E-mail</label>
      <input type="text" name="LOGIN" id="auth_login" class="field">
    </div>
    <div>
      <label class="label">Пароль</label>
      <input type="password" name="PASSWORD" id="auth_pass" class="field">
    </div>
    <div>
      <input type="button" onclick="SendGet();" class="button" id="button-auth" value="Войти">
      <a href="#recovery" class="forget">Забыли пароль?</a>
    </div>
  </form>
</div>
<!-- Модальное входа -->
<a href="#x" class="overlay" id="recovery"></a>

<div class="popup popup-recovery" style="display: block;">
	<a href="#" class="close"></a>
  <h3>Восстановление пароля</h3>
  <form class="popup-auth">
  <p id="message-rem"></p>
  	<div>
      <label class="label">Ваш email</label>
      <input type="text" class="field" id="remind-email" value="">
    </div>
    <div>
      <input type="button" onclick="Remind();" class="button" value="Новый пароль">
    </div>
  </form>
</div>
';  
    
}
	
?>


<script type="text/javascript" src='../js/addcart.js'></script>
<script type="text/javascript">loadcart();</script>
	<div class="basket" id="block-basket">
    <img src="../images/commerce.png" width="29" height="30" alt="Корзина"><a href="../cart.php?action=oneclick" />Корзина пуста</a>      
    </div>
  </header>
  <div id="toplineafter"></div>
