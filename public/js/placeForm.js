jQuery('input[name=edit]').click(function(){
  var checkboxValue = jQuery(this).val();
  var newAction = "/place/modify/" + checkboxValue;
  jQuery('#placeForm').attr('action', newAction);
  jQuery('#placeForm').submit();
});
jQuery('#delete_button').click(function(){
  var ids = [];
  $('input[name=delete_checkbox]:checked').each(function(){
    ids.push($(this).val());
  });
  $.ajax({
        method: "POST",
        url: '/purchase/place/remove',
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
