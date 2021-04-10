//**Hotel Name load start**//
function hotel_name_list_load(id)
{
  var count = id.substring(7);
  var city_id = $("#"+id).val();
  $.get( "inc/hotel_name_load.php" , { city_id : city_id } , function ( data ) {
        $ ("#hotel_id"+count).html( data ) ;                            
  } ) ;   
}

//**Hotel Name load end**//

function get_quotation_details(element){
  var base_url = $('#base_url').val();
  var quotation_id = $(element).val();
  if(quotation_id == ""){
    var table = document.getElementById('tbl_hotel_booking');
    for(var k=1; k<table.rows.length; k++){
      document.getElementById("tbl_hotel_booking").deleteRow(k);
    }
    $('#pass_name').val('');
    $('#adults').val('');
    $('#childrens').val('');
    $('#infants').val('');
    $('#sub_total').val(0);
    $('#sub_total').trigger('change');
  }
}