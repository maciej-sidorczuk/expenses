jQuery('input[name=edit]').click(function(){
  var checkboxValue = jQuery(this).val();
  var newAction = "/payment/modify/" + checkboxValue;
  jQuery('#paymentMethodForm').attr('action', newAction);
  jQuery('#paymentMethodForm').submit();
});
jQuery('#delete_button').click(function(){
  var ids = [];
  $('input[name=delete_checkbox]:checked').each(function(){
    ids.push($(this).val());
  });
  $.ajax({
        method: "POST",
        url: '/payment/remove',
        data : {id : ids}
    }).done(function(msg){
      if(msg.status == "ok") {
        alert('Records were deleted');
        location.reload();
      } else if(msg.status == "error") {
        alert(msg.message);
      } else {
        alert('Some error occured');
      }
    });
})
