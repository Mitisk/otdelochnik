function SendToCart(tiid) {
$.ajax({
  type: "POST",
  url: "/include/addtocart.php",
  data: "id="+tiid,
  dataType: "html",
  cache: false,
  success: function(data) { 
  loadcart();
      }
});

};

function loadcart(){
  $.ajax({
  type: "POST",
  url: "/include/loadcart.php",
  dataType: "html",
  cache: false,
  success: function(data) {
    
  if (data == "0")
  {
  
    $("#block-basket > a").html("Корзина пуста");
	
  }else
  {
    $("#block-basket > a").html(data);

  }  
    
      }
});    
       
}
function fun_group_price(intprice) {  
    // Группировка цифр по разрядам
  var result_total = String(intprice);
  var lenstr = result_total.length;
  
    switch(lenstr) {
  case 4: {
  groupprice = result_total.substring(0,1)+" "+result_total.substring(1,4);
    break;
  }
  case 5: {
  groupprice = result_total.substring(0,2)+" "+result_total.substring(2,5);
    break;
  }
  case 6: {
  groupprice = result_total.substring(0,3)+" "+result_total.substring(3,6); 
    break;
  }
  case 7: {
  groupprice = result_total.substring(0,1)+" "+result_total.substring(1,4)+" "+result_total.substring(4,7); 
    break;
  }
  default: {
  groupprice = result_total;  
  }
}  
    return groupprice;
    }