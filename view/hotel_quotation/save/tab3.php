<form id="frm_tab3">
	<div class="app_panel"> 

	<!--=======Header panel======-->
		<div class="app_panel_head mg_bt_20">
		<div class="container">
			<h2 class="pull-left"></h2>
			<div class="pull-right header_btn">
				<button>
					<a>
						<i class="fa fa-arrow-right"></i>
					</a>
				</button>
			</div>
		</div>
		</div> 
	<!--=======Header panel end======-->
        <div class="container" id="table_data">
		
		</div>

		<div class="row text-center mg_tp_20">
			<div class="col-xs-12">
				<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab2()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp Previous</button>
				&nbsp;&nbsp;
				<button id="btn_quotation_save" class="btn btn-info btn-sm ico_right">Save&nbsp;&nbsp;<i class="fa fa-floppy-o"></i></button>
			</div>
		</div>
	</div>
</form>

<script>
$('#dest_name').select2();
function switch_to_tab2(){ 
	$('#tab3_head').addClass('done');
	$('#tab2_head').addClass('active');
	$('.bk_tab').removeClass('active');
	$('#tab2').addClass('active');
	$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
 }

$('#frm_tab3').validate({

	submitHandler:function(form,e)
	{
		var nofquotation = $('#nofquotation').val();
		var quotation_date = $('#quotation_date').val();
		var base_url = $('#base_url').val();

		var enquiryDetails = {
			enquiry_id : $('#enquiry_id').val(),
			customer_name : $('#customer_name').val(),
			email_id : $('#email_id').val(),
			country_code : $('#country_code').val(),
			whatsapp_no : $('#whatsapp_no').val(),
			total_adult : $('#total_adult').val(),
			children_without_bed : $('#children_without_bed').val(),
			children_with_bed : $('#children_with_bed').val(),
			total_infant : $('#total_infant').val(),
			total_members : $('#total_members').val(),
			hotel_requirements : $('#hotel_requirements').val()
		};

		var optionJson = [];
		for(var quot = 1; quot <= nofquotation; quot++){
			var table = document.getElementById("dynamic_table_list_h_"+quot);
			var rowCount = table.rows.length;
			var eachHotel = [];
			for(var i=0; i<rowCount; i++){

				var row = table.rows[i];
				if(row.cells[0].childNodes[0].checked){	      

					eachHotel.push({
						city_id	:	row.cells[2].childNodes[0].value,
						hotel_id	:	row.cells[3].childNodes[0].value,
						hotel_cat	:	row.cells[4].childNodes[0].value,
						meal_plan : row.cells[5].childNodes[0].value,
						checkin	:	row.cells[6].childNodes[0].value,
						checkout	:	row.cells[7].childNodes[0].value,
						hotel_type : row.cells[8].childNodes[0].value,
						hotel_stay_days	:	row.cells[9].childNodes[0].value,
						total_rooms	:	row.cells[10].childNodes[0].value,
						extra_bed	:	row.cells[11].childNodes[0].value,
						hotel_cost	:	row.cells[12].childNodes[0].value
					});
				}
			}
			optionJson.push(eachHotel);
		}
		var costingJson = [];
		var bsmValues = [];
		for(var quot = 1; quot <= nofquotation; quot++){
			var table = document.getElementById("dynamic_quotation_costing_h_"+quot);
			var rowCount = table.rows.length;
				
			var row = table.rows[0];
			eachHotel = {
				hotel_cost	:	row.cells[0].childNodes[1].value,
				service_charge	:	row.cells[1].childNodes[1].value,
				tax_amount	:	row.cells[2].childNodes[1].value,
				markup_cost	:	row.cells[3].childNodes[1].value,
				markup_tax	:	row.cells[4].childNodes[1].value,
				roundoff	:	row.cells[5].childNodes[1].value,
				total_amount	:	row.cells[6].childNodes[1].value
			};
			costingJson.push(eachHotel);
				
			bsmValues.push({
				"basic" : $(row.cells[0].childNodes[0]).find('span').text(),
				"service" : $(row.cells[1].childNodes[0]).find('span').text(),
				"markup" : $(row.cells[4].childNodes[0]).find('span').text()
			});
		}
		$.post(base_url + '/controller/hotel/quotation/quotation_save.php',	{ nofquotation : nofquotation, optionJson : optionJson, costingJson : costingJson , enquiryDetails : enquiryDetails, quotation_date : quotation_date, bsmValues : bsmValues},	function(message){
			
			$('#btn_quotation_save').button('reset');
			var msg = message.split('--');

			if(msg[0]=="error"){
				error_msg_alert(msg[1]);
			}
			else{
					$('#vi_confirm_box').vi_confirm_box({
						false_btn: false,
						message: message,
						true_btn_text:'Ok',
						callback: function(data1){
							if(data1=="yes"){
								$('#btn_quotation_save').button('reset');
								window.location.href = base_url+'view/hotel_quotation/index.php';
							}
						}
					});
				}
		});
	}
});
</script>