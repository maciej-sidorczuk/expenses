//head icons functions
$('.filter_tool').click(function(){
  $('.filter_section').toggleClass('hide');
});
$('.add_tool').click(function(){
  $('#create_new').toggleClass('hide');
});
$('.close_ico').click(function(){
  $('#create_new').toggleClass('hide');
});
//head icons functions - end
//date functions
$( function() {
  $( "#datepicker1" ).datepicker();
    $( "#datepicker1" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
} );
$( function() {
  $( "#datepicker2" ).datepicker();
    $( "#datepicker2" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
} );
$( function() {
  $( "#datepicker3" ).datepicker();
    $( "#datepicker3" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
} );
//date functions - end
//sorting columns
function sortingColumn(classColumnName, orderValue) {
  $(classColumnName).off("click");
  $(classColumnName).click(function(){
    $('#select_order').val(orderValue);
    $("#criteria_form").removeClass();
    $(this).addClass('current');
    $('#result_append_section .column_header:not(.current) .updownarrows').removeClass('hide_arrows');
    $('#result_append_section .column_header:not(.current) .uparrows').addClass('hide_arrows');
    $('#result_append_section .column_header:not(.current) .downarrows').addClass('hide_arrows');
    $(this).removeClass('current');
    var classColumnNameWithoutDot = classColumnName.substr(1);
    if(!($(classColumnName + ' .updownarrows').hasClass('hide_arrows'))) {
      $('#select_order_direction').val("ASC");
      $("#criteria_form").addClass(classColumnNameWithoutDot + "_asc");
    } else if(!($(classColumnName + ' .uparrows').hasClass('hide_arrows'))) {
      $('#select_order_direction').val("DESC");
      $("#criteria_form").addClass(classColumnNameWithoutDot + "_desc");
    } else if(!($(classColumnName + ' .downarrows').hasClass('hide_arrows'))) {
      $('#select_order_direction').val("ASC");
      $("#criteria_form").addClass(classColumnNameWithoutDot + "_asc");
    }
    $("#criteria_form").submit();
  })
}
function changeSortingColumn(classColumnName) {
  var classColumnNameWithoutDot = classColumnName.substr(1);
  if($("#criteria_form").hasClass(classColumnNameWithoutDot + "_asc")) {
    $(classColumnName + ' .updownarrows').addClass('hide_arrows');
    $(classColumnName + ' .downarrows').addClass('hide_arrows');
    $(classColumnName + ' .uparrows').removeClass('hide_arrows');
    $(this).removeClass();
  }
  if($("#criteria_form").hasClass(classColumnNameWithoutDot + "_desc")) {
    $(classColumnName + ' .updownarrows').addClass('hide_arrows');
    $(classColumnName + ' .downarrows').removeClass('hide_arrows');
    $(classColumnName + ' .uparrows').addClass('hide_arrows');
    $(this).removeClass();
  }
}
$(document).on("expenseTableReady", function(){
  sortingColumn('.date_sort', 'purchase_date');
  sortingColumn('.product_sort', 'product');
  sortingColumn('.description_sort', 'description');
  sortingColumn('.weight_sort', 'weight');
  sortingColumn('.quantity_sort', 'quantity');
  sortingColumn('.price_sort', 'price');
  sortingColumn('.totalprice_sort', 'totalprice');
  sortingColumn('.typeofexpense_sort', 'typeofexpense');
  sortingColumn('.paymentmethod_sort', 'paymentmethod');
  sortingColumn('.categoryofexpense_sort', 'categoryofexpense');
  sortingColumn('.place_sort', 'place');
  sortingColumn('.comment_sort', 'comment');
  $('#delete_button').click(function(){
    var ids = [];
    $('input[name=delete_checkbox]:checked').each(function(){
      /*if(ids == "") {
        ids += $(this).val();
      } else {
        ids += ", " + $(this).val();
      }*/
      ids.push($(this).val());
    });
    $.ajax({
        method: "POST",
        url: '/expense/remove',
        data : {id : ids}
    }).done(function(msg){
      if(msg.status == "ok") {
        alert('Records were deleted');
        $("#criteria_form").submit();
      }
    });

    //make ajax request with ids to delete records then
    //refresh the table
    //$("#criteria_form").submit();
  });
});
//sorting columns - end
//form scripts
function searchElementbyAjax(elementName, searchUrl) {
  var selectElement = "#select_" + elementName;
  var createElement = "#create_" + elementName;
  var element_list = $(selectElement).html();
  $(createElement).keypress(function(){
    var element_string_name = $(createElement).val();
    if(element_string_name.length > 1) {
      $.ajax({
          method: "POST",
          url: searchUrl,
          data : {name : $(createElement).val()}
      }).done(function(msg){
        if(msg.status == "ok") {
          var results = msg.content;
          $(selectElement).empty();
          $( '<option value="0">Create new</option>' ).appendTo( selectElement );
          for(var i = 0; i < results.length; i++) {
            var id = results[i].id;
            var resultName = results[i].name;
            $( '<option value="' + id + '">' + resultName + '</option>' ).appendTo( selectElement );
          }
        }
      });
    }
  });
  $(selectElement).change(function(){
    $(createElement).val($(selectElement + ' option:selected').text());
  });
  $(createElement).on('input', function(){
    if($(createElement).val() == "") {
        //TODO take list from server. Don't waste browser memory? Check what would be faster.
        $(selectElement).html(element_list);
    }
  });
}
searchElementbyAjax('product', '/product/search');
searchElementbyAjax('payment_method', '/payment/search');
searchElementbyAjax('purchase_place', '/place/search');
searchElementbyAjax('expense_category', '/expensecategory/search');
searchElementbyAjax('type_expense', '/expensetype/search');

function checkAndCreateItem(itemType, itemUrlPart, itemName, data) {
  var showUrl = "/" + itemUrlPart + "/showbyname";
  var addUrl = "/" + itemUrlPart + "/add";
  var fieldId = itemType + "_id";
  $.ajax({
    method: "POST",
    url: showUrl,
    data: {name: itemName}
  })
  .done(function( msg ) {
    if(msg.status == "ok") {
      var results = msg.content;
      if(results.length > 0) {
        data[fieldId] = results[0].id;
      } else {
        $.ajax({
          method: "POST",
          url: addUrl,
          data: {name: itemName}
        })
        .done(function( msg ) {
          if(msg.status == "ok") {
            data[fieldId] = msg.id;
          } else {
            $("#create_result_area").empty();
            $("#create_result_area").text("Error. Can't create " + itemType);
            return;
          }
        });
      }
    }
  });
}

$("#create_expense").submit(function(e){
  e.preventDefault();
  var data = {};
  if($("#datepicker3").val() != "") {
    //TODO validation, field required
    data.date = $("#datepicker3").val();
  }
  if($("#create_description").val() != "") {
    //TODO validation
    data.description = $("#create_description").val();
  }
  if($("#create_weight").val() != "") {
    //TODO validation
    data.weight = $("#create_weight").val();
  }
  if($("#create_price").val() != "") {
    //TODO validation, field required
    data.price = $("#create_price").val();
  }
  if($("#create_quantity").val() != "") {
    //TODO validation, field required
    data.quantity = $("#create_quantity").val();
  }
  if($("#create_comment").val() != "") {
    //TODO validation
    data.comment = $("#create_comment").val();
  }
  if($("#select_product").val() == 0) {
    var productName = $("#create_product").val();
    checkAndCreateItem('product', 'product', productName, data);
  } else {
    data.product_id = $("#select_product").val();
  }
  if($("#select_type_expense").val() == 0) {
    var name = $("#create_type_expense").val();
    checkAndCreateItem('type_of_expense', 'expensetype', name, data);
  } else {
    data.type_of_expense_id = $("#select_type_expense").val();
  }
  if($("#select_payment_method").val() == 0) {
    var name = $("#create_payment_method").val();
    checkAndCreateItem('payment_method', 'payment', name, data);
  } else {
    data.payment_method_id = $("#select_payment_method").val();
  }
  if($("#select_expense_category").val() == 0) {
    var name = $("#create_expense_category").val();
    checkAndCreateItem('category_of_expense', 'expensecategory', name, data);
  } else {
    data.category_of_expense_id = $("#select_expense_category").val();
  }
  if($("#select_purchase_place").val() == 0) {
    var name = $("#create_purchase_place").val();
    checkAndCreateItem('place', 'purchase/place', name, data);
  } else {
    data.place_id = $("#select_purchase_place").val();
  }
  var ajaxStopExecuted = false;
  $( document ).ajaxStop(function() {
    if(!ajaxStopExecuted) {
      ajaxStopExecuted = true;
      $.ajax({
        method: "POST",
        url: "/expense/add",
        data
      })
      .done(function( msg ) {
        $("#create_result_area").empty();
        if(msg.status == "ok") {
          $("#create_result_area").empty();
          $("#create_result_area").text("Expense was added to database");
          if($('#same_shopping').attr('checked')) {
            //TODO clear some fields and reset selects
          } else {
            //TODO clear all fields and reset selects
          }
        } else if(msg.status == "error") {
          $("#create_result_area").empty();
          $("#create_result_area").text(msg.message);
        }
      });
    }
  });

});
$("#criteria_form").submit(function(e){
  e.preventDefault();
  var data = {};
  if($("#datepicker1").val() != "") {
    data.from_date = $("#datepicker1").val();
  }
  if($("#datepicker2").val() != "") {
    data.to_date = $("#datepicker2").val();
  }
  if($("#description").val() != "") {
    data.description = $("#description").val();
  }
  if($("#weight_min").val() != "") {
    data.weight_min = $("#weight_min").val();
  }
  if($("#weight_max").val() != "") {
    data.weight_max = $("#weight_max").val();
  }
  if($("#price_min").val() != "") {
    data.price_min = $("#price_min").val();
  }
  if($("#price_max").val() != "") {
    data.price_max = $("#price_max").val();
  }
  if($("#quantity_min").val() != "") {
    data.quantity_min = $("#quantity_min").val();
  }
  if($("#quantity_max").val() != "") {
    data.quantity_max = $("#quantity_max").val();
  }
  if($("#comment").val() != "") {
    data.comment = $("#comment").val();
  }
  var products_checkboxes= new Array();
  $.each($("input[name='product[]']:checked"), function() {
    products_checkboxes.push($(this).val());
  });
  data.product = products_checkboxes;
  var expensetype_checkboxes= new Array();
  $.each($("input[name='expense_type[]']:checked"), function() {
    expensetype_checkboxes.push($(this).val());
  });
  data.expense_type = expensetype_checkboxes;
  var paymentMethod_checkboxes= new Array();
  $.each($("input[name='payment[]']:checked"), function() {
    paymentMethod_checkboxes.push($(this).val());
  });
  data.payment = paymentMethod_checkboxes;
  var expenseCategory_checkboxes= new Array();
  $.each($("input[name='expense_category[]']:checked"), function() {
    expenseCategory_checkboxes.push($(this).val());
  });
  data.expense_category = expenseCategory_checkboxes;
  var purchasePlace_checkboxes= new Array();
  $.each($("input[name='place[]']:checked"), function() {
    purchasePlace_checkboxes.push($(this).val());
  });
  data.order = $("#select_order").val();
  data.order_direction = $("#select_order_direction").val();
  data.place = purchasePlace_checkboxes;

  $.ajax({
    method: "POST",
    url: "/expense/show",
    data
  }).done(function( msg ) {
    if(msg.status == "ok") {
    var results = msg.content;
    var string_result = "";
    for(var i = 0; i < results .length; i++) {
      var id = results[i][0].id;
      var date = results[i][0].purchaseDate;
      var format_date = date.substring(0, 10);
      var product_id = results[i][0].productId.id;
      var product = results[i][0].productId.name;
      var description = results[i][0].description;
      var weight = results[i][0].weight;
      var price = results[i][0].price;
      var quantity = results[i][0].quantity;
      var total_price = results[i].total_price;
      var type_of_expense_id = results[i][0].typeOfExpenseId.id;
      var type_of_expense = results[i][0].typeOfExpenseId.name;
      var payment_method_id = results[i][0].paymentMethodId.id;
      var payment_method= results[i][0].paymentMethodId.name;
      var category_of_expense_id = results[i][0].categoryOfExpenseId.id;
      var category_of_expense = results[i][0].categoryOfExpenseId.name;
      var place_id = results[i][0].placeId.id;
      var place = results[i][0].placeId.name;
      var comment = results[i][0].comment;
      var counter = i + 1;
      string_result += "<tr><td>" + counter  + "</td><td>" + format_date + "</td><td>" + product + "</td><td>" + description + "</td><td>" + weight + "</td><td>" + price + "</td><td>" + quantity + "</td><td>" + total_price + "</td><td>" + type_of_expense + "</td><td>" + payment_method + "</td><td>" + category_of_expense + "</td><td>" + place + "</td><td>" + comment + "</td>" + "<td><input type=\"radio\" name=\"edit\" value=\"" + id + "\"></td>" + "<td><input type=\"checkbox\" name=\"delete_checkbox\" value=\"" + id + "\"></td></tr>";
    }
    string_result += "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><button type=\"button\">Edit</button></td><td><button type=\"button\" id=\"delete_button\">Delete</button></td></tr>";
    $("#result_append_section table tbody tr:not(.table_header)").remove();
    $(string_result).appendTo("#result_append_section table tbody");
    changeSortingColumn('.date_sort');
    changeSortingColumn('.product_sort');
    changeSortingColumn('.description_sort');
    changeSortingColumn('.weight_sort');
    changeSortingColumn('.price_sort');
    changeSortingColumn('.quantity_sort');
    changeSortingColumn('.totalprice_sort');
    changeSortingColumn('.typeofexpense_sort');
    changeSortingColumn('.paymentmethod_sort');
    changeSortingColumn('.categoryofexpense_sort');
    changeSortingColumn('.place_sort');
    changeSortingColumn('.comment_sort');
    $(document).trigger("expenseTableReady");
  }
});
});
//form scripts - end
