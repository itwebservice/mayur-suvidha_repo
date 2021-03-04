<?php
include "../../../model/model.php";
$bankId = $_POST['bank_id'];
?>
<input type="hidden" id="bank_id" value="<?= $bankId ?>">
<form id="frm_save">
<div class="modal fade" id="send_details" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Send Bank Details</h4>
      </div>
      <div class="modal-body">
        
		<div class="row">
			<div class="col-md-3" style="padding-right:0px;">
                <select name="country_code" id="country_code" style="width:135px;">
                	<?= get_country_code(); ?>
                </select>
            </div>
            <div class="col-md-3">
				<input type="text" id="whatsapp_no" name="whatsapp_no" class="bank_suggest" placeholder="Whatsapp Number" title="Whatsapp Number"> 
            </div>
			<div class="col-sm-6 mg_bt_10">
				<input type="text" id="email_id" name="email_id" placeholder="Email Id" title="Email Id">
			</div>
		</div>
		<div class="row text-center mg_tp_20">
			<button type="button" onclick="whatsapp_send()" class="btn btn-sm btn-info ico_left"><i class="fa fa-whatsapp"></i>&nbsp;&nbsp;Send WhatsApp</button>&nbsp;&nbsp;
            <button class="btn btn-sm btn-info ico_right">Send Email&nbsp;&nbsp;<i class="fa fa-envelope"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</form>

<script>
$('#country_code').select2();
$('#send_details').modal('show');
$('#as_of_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
$(function(){
  $('#frm_save').validate({
    rules:{
		},
    submitHandler:function(form){

        var base_url = $('#base_url').val();
         
		var emailId = $('#email_id').val();
		if(emailId == ''){
			error_msg_alert('Please Enter EMail Id');
			return false;
		}
        var bankId = $('#bank_id').val();

		$('#btn_save').button('loading');
		$.post(
					base_url+"controller/finance_master/bank_master/send_email.php",
					{ emailId : emailId, bankId : bankId },
					function(data) {
						$('#btn_save').button('reset');
						var msg = data.split('--');
						if(msg[0]=="error"){
							error_msg_alert(msg[1]);
						}else{
							msg_alert(data);
							$('#save_modal').modal('hide');
							$('#save_modal').on('hidden.bs.modal', function(){
								list_reflect();
							});
						}
		});
    }
  });
});
function whatsapp_send(){
	var whatsapp_no = $('#country_code').val()+$('#whatsapp_no').val();
	var base_url = $('#base_url').val();
	var bankId = $('#bank_id').val();
	if($('#country_code').val() == ''){
		error_msg_alert('Please Enter Country Code');
		return false;
	}
	if($('#whatsapp_no').val() == ''){
		error_msg_alert('Please Enter Whatsapp Number');
		return false;
	}
	$.get(
					base_url+"controller/finance_master/bank_master/send_whatsapp.php",
					{ whatsapp_no : whatsapp_no, bankId : bankId },
					function(data) {
						$('#btn_save').button('reset');
						window.open(data);
		});
}
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>