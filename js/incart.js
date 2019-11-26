function CountMinus(iid) {
$.ajax({
  type: "POST",
  url: "/include/count-minus.php",
  data: "id="+iid,
  dataType: "html",
  cache: false,
  success: function(data) {   
  $("#input-id"+iid).val(data);  
  loadcart();
  
  // переменная с ценной продукта
  var priceproduct = $("#tovar"+iid+" > p").attr("price"); 
  // Цену умножаем на колличество
  result_total = Number(priceproduct) * Number(data);
 
  $("#tovar"+iid+" > p").html(fun_group_price(result_total)+" руб");
  $("#tovar"+iid+" > h5 > .span-count").html(data);
  
  itog_price();
      }
});
  
};

function CountPlus(iid) {
$.ajax({
  type: "POST",
  url: "/include/count-plus.php",
  data: "id="+iid,
  dataType: "html",
  cache: false,
  success: function(data) {   
  $("#input-id"+iid).val(data);  
  loadcart();
  
  // переменная с ценной продукта
  var priceproduct = $("#tovar"+iid+" > p").attr("price"); 
  // Цену умножаем на колличество
  result_total = Number(priceproduct) * Number(data);
 
  $("#tovar"+iid+" > p").html(fun_group_price(result_total)+" руб");
  $("#tovar"+iid+" > h5 > .span-count").html(data);
  
  itog_price();
      }
});
  
};


function  itog_price(){
 
 $.ajax({
  type: "POST",
  url: "/include/itog_price.php",
  dataType: "html",
  cache: false,
  success: function(data) {

  $(".itog-price > strong").html(data);

}
}); 
       
}