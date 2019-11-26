<?php
defined('shoppromoauto') or die ('Загрузка...');
function group_numerals($int){
    
       switch (strlen($int)) {

	    case '4':
        
        $price = substr($int,0,1).' '.substr($int,1,4);

	    break;

	    case '5':
        
        $price = substr($int,0,2).' '.substr($int,2,5);

	    break;

	    case '6':
        
        $price = substr($int,0,3).' '.substr($int,3,6);

	    break;

	    case '7':
        
        $price = substr($int,0,1).' '.substr($int,1,3).' '.substr($int,4,7);

	    break;
        
	    default:
        
        $price = $int;
        
	    break;

	}
    return $price; 
}
?>