<?php

include "../../../model/model.php";

?>
<input type="hidden" id="table_id_show" name="table_id_show" value="<?= $_POST['table_id'] ?>">
<form id="frm_save">

<div class="modal fade" id="save_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Room Category</h4>

      </div>

      <div class="modal-body">

        

          <div class="row">

            <div class="col-sm-8 col-sm-offset-2 mg_bt_10">

              <input type="text" id="room_category" name="room_category" onchange="fname_validate(this.id);" placeholder="*Room Category" title="Room Category">

            </div>

             <div class="col-sm-4 mg_bt_10_sm_xs">

              <select name="active_flag" id="active_flag" title="Status" style="width:100%" class="hidden">

                <option value="Active">Active</option>

                <option value="Inactive">Inactive</option>

              </select>

            </div>

          </div>

          <div class="row mg_tp_10">
            <div class="col-xs-12 text-center">
              <button class="btn btn-sm btn-success" id="btn_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
            </div>
          </div>



      </div>      

    </div>

  </div>

</div>

</form>

<script>

$('#save_modal').modal('show');

$('#frm_save').validate({

    rules:{

            room_category : { required : true }

    },

    submitHandler:function(form){



        var room_category = $('#room_category').val();

        var status = $('#active_flag').val();



        $('#btn_save').button('loading');



        $.ajax({

          type:'post',

          url:base_url()+'controller/other_masters/room_category/save_room_category.php',

          data:{ room_category : room_category, status : status},

          success:function(result){

              $('#btn_save').button('reset');

              var msg = result.split('--');

              if(msg[0] == "error"){
                error_msg_alert(msg[1]);
              }else{
                if($('#table_id_show').val() != ''){
                    var roomCat = msg[0].split(':')[0];

                    var table=document.getElementById($('#table_id_show').val());
                    var rowlength = table.rows.length;

                    for(var i =0; i<rowlength;i++){
                      var newOption = new Option(roomCat, roomCat, false, false);
                      $('#'+table.rows[i].cells[4].childNodes[0].id).append(newOption);
                    }
                  }
                  msg_alert(msg[1]);
                   $('#save_modal').modal('hide');

                  list_reflect();
              }

          }

        });







    }

});

</script>

<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>