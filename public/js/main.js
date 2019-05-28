//head icons functions
$('.filter_tool').click(function(){
  $('.filter_section').toggleClass('hide');
});
$('.add_tool').click(function(){
  $('#create_new').toggleClass('hide');
  $('#create_new .list_hint').each(function(){
    var inputElement = $(this).siblings('input');
    var leftpos = $(inputElement).position().left;
    $(this).css('left', leftpos + 'px');
  });
  //$('.product_list').css('left', $('#create_product').position().left + 'px');
});
$('.close_ico').click(function(){
  $(this).parent().toggleClass('hide');
  $("#edit_result_area").empty();
});
$('.close_ico_filter').click(function(){
  $(this).parent().parent().toggleClass('hide');
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
$( function() {
  $( "#edit_datepicker3" ).datepicker();
    $( "#edit_datepicker3" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
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
jQuery('#create_expense .form_row input').focus(function(){
  var current_object = jQuery(this);
  jQuery('#create_expense .form_row input').each(function(){
    if(current_object == jQuery(this)) {
      return true;
    }
    jQuery(this).next().empty();
  });
});
function searchElementbyAjax(elementName, searchUrl, formType) {
  var createElement = formType + elementName;
  var listClass = "." + elementName + "_list";
  $(createElement).keyup(function(){
    var element_string_name = $(createElement).val();
    if(element_string_name.length > 0) {
      if(element_string_name.length == 1 && element_string_name != "*") {
        return;
      }
      $.ajax({
          method: "POST",
          url: searchUrl,
          data : {name : $(createElement).val()}
      }).done(function(msg){
        if(msg.status == "ok") {
          var results = msg.content;
          $(listClass).empty();
          $(createElement).attr('data-id', 0);
          var userInput = $(createElement).val().trim().toLowerCase();
          for(var i = 0; i < results.length; i++) {
            var id = results[i].id;
            var resultName = results[i].name;
            if(resultName.trim().toLowerCase() == userInput) {
              $(createElement).val(resultName);
              $(createElement).attr('data-id', id);
            }
            $('<li class="list_pos" data-id="' + id + '">' + resultName + '</li>').appendTo(listClass);
          }
          $(createElement).siblings(listClass).find('.list_pos').off();
          $(createElement).siblings(listClass).find('.list_pos').click(function(){
            var element_id = $(this).attr("data-id");
            var element_name = $(this).text();
            $(createElement).val(element_name);
            $(createElement).attr('data-id', element_id);
            $(listClass).empty();
          });
        }
      });
    }
  });
}
searchElementbyAjax('product', '/product/search', '#create_');
searchElementbyAjax('payment_method', '/payment/search', '#create_');
searchElementbyAjax('purchase_place', '/place/search', '#create_');
searchElementbyAjax('expense_category', '/expensecategory/search', '#create_');
searchElementbyAjax('type_expense', '/expensetype/search', '#create_');
searchElementbyAjax('product', '/product/search', '#edit_');
searchElementbyAjax('payment_method', '/payment/search', '#edit_');
searchElementbyAjax('purchase_place', '/place/search', '#edit_');
searchElementbyAjax('expense_category', '/expensecategory/search', '#edit_');
searchElementbyAjax('type_expense', '/expensetype/search', '#edit_');

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

function ajaxExpenseAdd(data) {
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
  var needAjaxRequest = false;
  if($("#create_product").attr('data-id') == 0) {
    var productName = $("#create_product").val();
    needAjaxRequest = true;
    checkAndCreateItem('product', 'product', productName, data);
  } else {
    data.product_id = $("#create_product").attr('data-id');
  }
  if($("#create_type_expense").attr('data-id') == 0) {
    var name = $("#create_type_expense").val();
    needAjaxRequest = true;
    checkAndCreateItem('type_of_expense', 'expensetype', name, data);
  } else {
    data.type_of_expense_id = $("#create_type_expense").attr('data-id');
  }
  if($("#create_payment_method").attr('data-id') == 0) {
    var name = $("#create_payment_method").val();
    needAjaxRequest = true;
    checkAndCreateItem('payment_method', 'payment', name, data);
  } else {
    data.payment_method_id = $("#create_payment_method").attr('data-id');
  }
  if($("#create_expense_category").attr('data-id') == 0) {
    var name = $("#create_expense_category").val();
    needAjaxRequest = true;
    checkAndCreateItem('category_of_expense', 'expensecategory', name, data);
  } else {
    data.category_of_expense_id = $("#create_expense_category").attr('data-id');
  }
  if($("#create_purchase_place").attr('data-id') == 0) {
    var name = $("#create_purchase_place").val();
    needAjaxRequest = true;
    checkAndCreateItem('place', 'purchase/place', name, data);
  } else {
    data.place_id = $("#create_purchase_place").attr('data-id');
  }
  var ajaxStopExecuted = false;
  if(needAjaxRequest) {
    $( document ).ajaxStop(function() {
      if(!ajaxStopExecuted) {
        ajaxStopExecuted = true;
        ajaxExpenseAdd(data);
      }
    });
  } else {
    ajaxExpenseAdd(data);
  }

});

function getAndValidateDataFromCriteriaForm() {
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
  return data;
}
var data_for_chart;
var data_for_category_chart;
var data_for_place_chart;
var data_for_typeOfExpense_chart;
var data_for_paymentMethod_chart;
$("#criteria_form").submit(function(e){
  e.preventDefault();
  var data = getAndValidateDataFromCriteriaForm();
  $.ajax({
    method: "POST",
    url: "/expense/show",
    data
  }).done(function( msg ) {
    if(msg.status == "ok") {
    var results = msg.content;
    if(results.length >= 1) {
      jQuery("#export_csv_button").prop('disabled', false);
    } else {
      jQuery("#export_csv_button").prop('disabled', true);
    }
    var calculations = msg.calculations;
    var timeinfo = msg.timeinfo;
    var string_result = "";
    var calculations_result = "";
    data_for_chart = new Map();
    data_for_category_chart = new Map();
    data_for_place_chart = new Map();
    data_for_typeOfExpense_chart = new Map();
    data_for_paymentMethod_chart = new Map();
    for(var i = 0; i < results.length; i++) {
      var id = results[i][0].id;
      var date = results[i][0].purchaseDate;
      var format_date = date.substring(0, 10);
      var product_id = results[i][0].productId.id;
      var product = results[i][0].productId.name;
      var description = results[i][0].description;
      var weight = results[i][0].weight;
      weight = parseFloat(weight).toFixed(2);
      var price = results[i][0].price;
      price = parseFloat(price).toFixed(2);
      var quantity = results[i][0].quantity;
      var total_price = results[i].total_price;
      total_price = parseFloat(total_price).toFixed(2);
      if(data_for_chart.has(format_date)) {
        current_count_price = parseFloat(data_for_chart.get(format_date));
        sum_price = current_count_price + parseFloat(total_price);
        data_for_chart.set(format_date, sum_price.toFixed(2));
      } else {
        data_for_chart.set(format_date, total_price);
      }
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
      string_result += "<tr><td class=\"rowNumber\">" + counter  + "</td><td class=\"rowDate\">" + format_date + "</td><td data-id=\"" + product_id + "\" class=\"rowProduct\">" + product + "</td><td class=\"rowDescription\">" + description + "</td><td class=\"rowWeight\">" + weight + "</td><td class=\"rowPrice\">" + price + "</td><td class=\"rowQuantity\">" + quantity + "</td><td class=\"rowTotalPrice\">" + total_price + "</td><td data-id=\"" + type_of_expense_id + "\" class=\"rowTypeOfExpense\">" + type_of_expense + "</td><td data-id=\"" + payment_method_id + "\" class=\"rowPaymentMethod\">" + payment_method + "</td><td data-id=\"" + category_of_expense_id +"\" class=\"rowCategoryOfExpense\">" + category_of_expense + "</td><td data-id=\"" + place_id +"\" class=\"rowPlace\">" + place + "</td><td class=\"rowComment\">" + comment + "</td>" + "<td><input type=\"radio\" name=\"edit\" value=\"" + id + "\"></td>" + "<td><input type=\"checkbox\" name=\"delete_checkbox\" value=\"" + id + "\"></td></tr>";
    }
    console.log(data_for_chart);
    if(results.length > 0) {
      string_result += "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><button type=\"button\" id=\"delete_button\">Delete</button></td></tr>";
      $("#result_append_section table tbody tr:not(.table_header)").remove();
      $(string_result).appendTo("#result_append_section table tbody");
      calculations_result += "<div class=\"info-stat\">Calculation statistics (" + timeinfo + "):</div>";
      calculations_result += "<div class=\"single-stat\"><div class=\"flex-container\"><div class=\"stat-key\">" + "Sum of expenses: " + "</div><div class=\"stat-value\">" + calculations['total'].toFixed(2) + "</div><div class=\"stat-percentage\">(" + (calculations['total']/calculations['total'] * 100).toFixed(2) + "%)</div></div>";
      calculations_result += "</div>";

      calculations_result += "<div class=\"single-stat\"><div class=\"info\">Expense Categories: </div>";
      var categoryObject = calculations['categories'];
      for (var property in categoryObject) {
        if (categoryObject.hasOwnProperty(property)) {
            calculations_result += "<div class=\"flex-container\"><div class=\"stat-key\">" + property + ": </div><div class=\"stat-value\">" + categoryObject[property].toFixed(2) + "</div><div class=\"stat-percentage\">(" + (categoryObject[property]/calculations['total'] * 100).toFixed(2) + "%)</div></div>";
            data_for_category_chart.set(property, categoryObject[property].toFixed(2));
        }
      }
      calculations_result += "</div>";

      calculations_result += "<div class=\"single-stat\"><div class=\"info\">Type of expense: </div>";
      var typeOfExpenseObject = calculations['typeofexpense'];
      for (var property in typeOfExpenseObject ) {
        if (typeOfExpenseObject.hasOwnProperty(property)) {
            calculations_result += "<div class=\"flex-container\"><div class=\"stat-key\">" + property + ": </div><div class=\"stat-value\">" + typeOfExpenseObject[property].toFixed(2) + "</div><div class=\"stat-percentage\">(" + (typeOfExpenseObject[property]/calculations['total'] * 100).toFixed(2) + "%)</div></div>";
            data_for_typeOfExpense_chart.set(property, typeOfExpenseObject[property].toFixed(2));
        }
      }
      calculations_result += "</div>";

      calculations_result += "<div class=\"single-stat\"><div class=\"info\">Place: </div>";
      var placeObject = calculations['place'];
      for (var property in placeObject ) {
        if (placeObject.hasOwnProperty(property)) {
            calculations_result += "<div class=\"flex-container\"><div class=\"stat-key\">" + property + ": </div><div class=\"stat-value\">" + placeObject[property].toFixed(2) + "</div><div class=\"stat-percentage\">(" + (placeObject[property]/calculations['total'] * 100).toFixed(2) + "%)</div></div>";
            data_for_place_chart.set(property, placeObject[property].toFixed(2));
        }
      }
      calculations_result += "</div>";

      calculations_result += "<div class=\"single-stat\"><div class=\"info\">Payment method: </div>";
      var paymentMethodObject = calculations['paymentmethod'];
      for (var property in paymentMethodObject ) {
        if (paymentMethodObject.hasOwnProperty(property)) {
            calculations_result += "<div class=\"flex-container\"><div class=\"stat-key\">" + property + ": </div><div class=\"stat-value\">" + paymentMethodObject[property].toFixed(2) + "</div><div class=\"stat-percentage\">(" + (paymentMethodObject[property]/calculations['total'] * 100).toFixed(2) + "%)</div></div>";
            data_for_paymentMethod_chart.set(property, paymentMethodObject[property].toFixed(2));
        }
      }
      calculations_result += "</div>";
    } else {
      $("#result_append_section table tbody tr:not(.table_header)").remove();
      calculations_result += "<div><p>No records were found</p></div>";
    }

    $("#calculations").empty();
    $(calculations_result).appendTo("#calculations");
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
jQuery("#export_csv_button").click(function(){
  $("#download_csv_form").empty();
  $("#criteria_form :input").clone().appendTo("#download_csv_form");
  $("#download_csv_form :input").each(function(){
    if($(this).get(0).type == "submit") {
      $(this).remove();
    }
    $(this).css("visibility", "hidden");
    $(this).css("position", "absolute");
    $(this).css("width", 0);
    $(this).css("height", 0);
    $(this).css("padding", 0);
    $(this).css("margin", 0);
    $(this).removeAttr('id');
  });
  $("#download_csv_form select[name=order]").val($("#criteria_form select#select_order").val());
  $("#download_csv_form select[name=order_direction]").val($("#criteria_form select#select_order_direction").val());
  $("#download_csv_form").submit();
  $("#download_csv_form").empty();
});
//form scripts - end
//edit form
$(document).on("expenseTableReady", function(){
  $('input[name="edit"]').off();
  $('input[name="edit"]').click(function(){
    var inputValue = $(this).val();
    $('#edit_expense_section').removeClass('hide');
    $('#edit_expense .list_hint').each(function(){
      var inputElement = $(this).siblings('input');
      var leftpos = $(inputElement).position().left;
      $(this).css('left', leftpos + 'px');
    });
    $('#edit_datepicker3').val($(this).parent().parent().find('.rowDate').text());
    $('#edit_description').val($(this).parent().parent().find('.rowDescription').text());
    $('#edit_weight').val($(this).parent().parent().find('.rowWeight').text());
    $('#edit_price').val($(this).parent().parent().find('.rowPrice').text());
    $('#edit_quantity').val($(this).parent().parent().find('.rowQuantity').text());

    $('#edit_type_expense').val($(this).parent().parent().find('.rowTypeOfExpense').text());
    $('#edit_type_expense').attr('data-id', $(this).parent().parent().find('.rowTypeOfExpense').attr('data-id'));
    $('#edit_payment_method').val($(this).parent().parent().find('.rowPaymentMethod').text());
    $('#edit_payment_method').attr('data-id', $(this).parent().parent().find('.rowPaymentMethod').attr('data-id'));
    $('#edit_product').val($(this).parent().parent().find('.rowProduct').text());
    $('#edit_product').attr('data-id', $(this).parent().parent().find('.rowProduct').attr('data-id'));
    $('#edit_expense_category').val($(this).parent().parent().find('.rowCategoryOfExpense').text());
    $('#edit_expense_category').attr('data-id', $(this).parent().parent().find('.rowCategoryOfExpense').attr('data-id'));
    $('#edit_purchase_place').val($(this).parent().parent().find('.rowPlace').text());
    $('#edit_purchase_place').attr('data-id', $(this).parent().parent().find('.rowPlace').attr('data-id'));

    $('#edit_comment').val($(this).parent().parent().find('.rowComment').text());
    $('#edit_expenseId').val(inputValue);
  });
});

$("#edit_expense").submit(function(e){
  e.preventDefault();
  //TODO integrate with create function and make also in this step create products, categories, payments etc.
  console.log("wykonanie");
  var data = {};
  if($("#edit_datepicker3").val() != "") {
    //TODO validation, field required
    data.date = $("#edit_datepicker3").val();
  }
  if($("#edit_description").val() != "") {
    //TODO validation
    data.description = $("#edit_description").val();
  }
  if($("#edit_weight").val() != "") {
    //TODO validation
    data.weight = $("#edit_weight").val();
  }
  if($("#edit_price").val() != "") {
    //TODO validation, field required
    data.price = $("#edit_price").val();
  }
  if($("#edit_quantity").val() != "") {
    //TODO validation, field required
    data.quantity = $("#edit_quantity").val();
  }
  if($("#edit_comment").val() != "") {
    //TODO validation
    data.comment = $("#edit_comment").val();
  }
  if($("#edit_product").attr('data-id') != 0) {
    data.product_id = $("#edit_product").attr('data-id');
  }
  if($("#edit_type_expense").attr('data-id') != 0) {
    data.type_of_expense_id = $("#edit_type_expense").attr('data-id');
  }
  if($("#edit_payment_method").attr('data-id') != 0) {
    data.payment_method_id = $("#edit_payment_method").attr('data-id');
  }
  if($("#edit_expense_category").attr('data-id') != 0) {
    data.category_of_expense_id = $("#edit_expense_category").attr('data-id');
  }
  if($("#edit_purchase_place").attr('data-id') != 0) {
    data.place_id = $("#edit_purchase_place").attr('data-id');
  }
  data.id = $("#edit_expenseId").val();
  $.ajax({
    method: "POST",
    url: "/expense/edit",
    data
  })
  .done(function( msg ) {
    $("#edit_result_area").empty();
    if(msg.status == "ok") {
      $("#edit_result_area").empty();
      $("#edit_result_area").text("Expense was updated. This window will close in 3 seconds");
      setTimeout(function(){
        $("#edit_result_area").empty();
        $('#edit_expense_section').addClass('hide');
      }, 3000);
      //refresh table
      $("#criteria_form").submit();
    } else if(msg.status == "error") {
      $("#edit_result_area").empty();
      $("#edit_result_area").text(msg.message);
    }
  });

});
//edit form - end
