<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
define('shoppromoauto', true);
include("dbconn.php");
include("../functions/sec.php");
        
$id = clear_string($_POST["id"]);

$result = mysql_query("SELECT * FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND cart_id_product = '$id'",$link);
If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);    
$new_count = $row["cart_count"] + 1;
$update = mysql_query ("UPDATE cart SET cart_count='$new_count' WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND cart_id_product = '$id'",$link);   
}
else
{
    $result = mysql_query("SELECT * FROM products WHERE id = '$id'",$link);
    $row = mysql_fetch_array($result);
    
    		mysql_query("INSERT INTO cart(cart_id_product,cart_price,cart_datetime,cart_ip)
						VALUES(	
                            '".$row['id']."',
                            '".$row['price']."',					
							NOW(),
                            '".$_SERVER['REMOTE_ADDR']."'                                                                        
						    )",$link);	
}
}
?>