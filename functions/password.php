<?php
defined('shoppromoauto') or die ('Загрузка...');
function fungenpass()
{
    $number = 7;

    $arr = array('a','b','c','d','e','f',

                 'g','h','i','j','k','l',

                 'm','n','o','p','r','s',

                 't','u','v','x','y','z',

                 '1','2','3','4','5','6',

                 '7','8','9','0');

    // Ãåíåðèðóåì ïàðîëü

    $pass = "";

    for($i = 0; $i < $number; $i++)

    {

      // Âû÷èñëÿåì ñëó÷àéíûé èíäåêñ ìàññèâà

      $index = rand(0, count($arr) - 1);

      $pass .= $arr[$index];

    }


return $pass;
}


function send_mail($from,$to,$subject,$body)
{
	$charset = 'utf-8';
	mb_language("ru");
	$headers  = "MIME-Version: 1.0 \n" ;
	$headers .= "From: <".$from."> \n";
	$headers .= "Reply-To: <".$from."> \n";
	$headers .= "Content-Type: text/html; charset=$charset \n";
	
	$subject = '=?'.$charset.'?B?'.base64_encode($subject).'?=';

	mail($to,$subject,$body,$headers);
}
?>