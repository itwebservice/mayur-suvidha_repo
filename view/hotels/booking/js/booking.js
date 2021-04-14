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
  $.getJSON(base_url + '/view/hotels/booking/booking/get_quotation_details.php', {quotation_id : quotation_id}, function(data){
    var table = document.getElementById('tbl_hotel_booking');
    for(var k=1; k<table.rows.length; k++){
      document.getElementById("tbl_hotel_booking").deleteRow(k);
    }
    $.each(data.hotel_details, function(i, field){
      var row = table.rows[i];
      $(row.cells[2].childNodes[0]).select2('destroy');
      $(row.cells[2].childNodes[0]).append('<option value="'+field.city_id+'" selected>'+field.city_name+'</option>');
      city_lzloading('#'+row.cells[2].childNodes[0].id);
      $(row.cells[3].childNodes[0]).append('<option value="'+field.hotel_id+'" selected>'+field.hotel_name+'</option>');
      row.cells[4].childNodes[0].value = field.checkin+' 00:00:00';
      row.cells[5].childNodes[0].value = field.checkout+' 00:00:00';
      row.cells[6].childNodes[0].value = field.hotel_stay_days;
      row.cells[7].childNodes[0].value = field.total_rooms;
      row.cells[9].childNodes[0].value = field.hotel_cat;
      row.cells[11].childNodes[0].value = field.extra_bed;
      row.cells[12].childNodes[0].value = field.meal_plan;
      $('#pass_name').val(data.enquiry_details.customer_name);
      $('#adults').val(data.enquiry_details.total_adult);
      $('#childrens').val(Number(data.enquiry_details.children_without_bed) + Number(data.enquiry_details.children_with_bed));
      $('#infants').val(data.enquiry_details.total_infant);
      if(i < data.hotel_details.length-1){
        addRow('tbl_hotel_booking');
      }

      $('#sub_total').val(data.costing_details.hotel_cost);
      $('#sub_total').trigger('change');
    });
  });
}