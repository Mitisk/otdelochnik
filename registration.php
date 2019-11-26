<?php 
   define('shoppromoauto', true);
ini_set('display_errors', 'Off');
   include("include/dbconn.php");
   include("include/auth_cookie.php");
   include("functions/sec.php");
   	//капча
	define( API_PUBLIC_KEY, '6LcMyB8TAAAAAEvCv7AjviFCXIAUJovetnEdEXie' );
	define( API_PRIVATE_KEY, '6LcMyB8TAAAAAEUAZR2DpRVSBRHAnTjuJw_DCk9G'  );
	require_once('reg/recaptchalib.php');
	$validated = false;
   ?>
  <html>
   <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <link rel="stylesheet" href="css/style.css" type="text/css" />
       <link rel="stylesheet" href="css/reset.css" type="text/css" />
       <link rel="stylesheet" href="css/modal.css" type="text/css" />
    <title>Отделочник.рф - регистрация</title>
    <script type="text/javascript" src='https://www.google.com/recaptcha/api.js'></script>
    <!-- Валидация -->
    <script type="text/javascript" src='../js/jquery-1.8.2.min.js'></script>
    <script type="text/javascript" src='../js/jquery.validate.js'></script>
    <script type="text/javascript" src='../js/jquery.form.js'></script>
    <script type="text/javascript" src="../js/TextChange.js"></script>  

	<script type="text/javascript">
	function RegSend() {
	event.preventDefault(); // прерываем отправку формы
	var msg   = $('#regform').serialize();
        $.ajax({
            type: "POST",
            url:"../reg/reg.php",
            data: msg,
            beforeSend: function() {
                $("#message").html('Загрузка...');
            },
            success: function(data) {
			if (data == 'true') { 
			$("#block_registration").fadeOut(300);
			$("#message").addClass("message-remind-success").fadeIn(400).html("<center><br><H3>Вы успешно зарегистрированы!</H3><br><a href='/index.php#enter'>ВХОД</a></center>");
			} else {
			$("#message").addClass("message-remind-error").fadeIn(400).html(data); 
			}
			}
		
        });
 
        return false;
    };
	</script>

   </head>
   <body>
   <div id="wrapper">
   <?php include("include/header.php"); ?>
   <?php include("include/slider.php"); ?>
   <br>
   <section class="content normal">
   
<H2>Регистрация</H2>
<p id="message"></p>
  <form class="popup-auth" id="regform" method="POST" action="http://shop.re/reg/reg.php">
  
  <div id="block_registration">
  <div style="float:left; width:40%;">
  	<div>
      <label class="label">Логин</label>
      <input type="text" name="login" id="login" class="field">
    </div>
    <div>
      <label class="label">Фамилия</label>
      <input type="text" name="surname" id="surname" class="field">
    </div>
    <div>
      <label class="label">Имя</label>
      <input type="text" name="name" id="name" class="field">
    </div>
    </div>  <div style="float:right; width:40%;">
        <div>
      <label class="label">E-mail</label>
      <input type="text" name="mail" id="mail" class="field">
    </div>
    <div>
      <label class="label">Пароль</label>
      <input type="password" name="password" id="password" class="field">
    </div>
   <?php echo recaptcha_get_html(API_PUBLIC_KEY); ?>
<br>
</div>
    <div>
	<input type="hidden" name="validate" value="yes" />
      <input onClick="RegSend()" type="submit" class="button" name="submit" value="Регистрация">
    </div>
    </div>
  </form>

  
   </section>
   <?php include("include/footer.php"); ?>
   </div>
   </body>
  </html>