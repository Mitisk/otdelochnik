<?php
defined('shoppromoauto') or die ('Загрузка...');
$db_host = "localhost";
$db_user = "u19590_kursovik";   
$db_pass = "5E9p8G7g";  
$db_database = "u19590_u19590_s3_radisol_org";

$link = mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_database,$link) or die("Технические работы :(".mysql_error());
mysql_query("SET names utf8");
ini_set('display_errors', 'Off');
?>