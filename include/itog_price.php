<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
define('shoppromoauto', true);
include('dbconn.php');
include('../functions/sec.php');
include('../functions/cartprice.php');

$result = mysql_query("SELECT * FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}'",$link);
If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);
  
do
{
    $int = $int + ($row["cart_price"] * $row["cart_count"]); 

} while($row = mysql_fetch_array($result));


     echo group_numerals($int);

}
}
?>