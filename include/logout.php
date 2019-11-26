<?php
      session_start();
	  unset($_SESSION['auth']);
      setcookie('promoauto','',0,'/');
	  header('Location: http://промоавто.рф/');
?>