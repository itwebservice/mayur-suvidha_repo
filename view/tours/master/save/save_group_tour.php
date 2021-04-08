<?php 
include "../../../../model/model.php";
include_once('../../../layouts/fullwidth_app_header.php'); 
?>
<style>
#save_modal1{
  /* z-index: 5023 !important; */
}
</style>
<!-- <div class="modal fade" id="save_modal1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Group Tour</h4>
      </div>
      <div class="modal-body"> -->


      <div class="bk_tab_head bg_light">
    <ul> 
        <li>
            <a href="javascript:void(0)" id="tab1_head" class="active">
                <span class="num" title="Tour">1<i class="fa fa-check"></i></span><br>
                <span class="text">Tour</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" id="tab2_head">
                <span class="num" title="Travelling">2<i class="fa fa-check"></i></span><br>
                <span class="text">Travelling</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" id="tab_daywise_head">
                <span class="num" title="Daywise Gallery">3<i class="fa fa-check"></i></span><br>
                <span class="text">Daywise Images</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" id="tab3_head">
                <span class="num" title="Costing">4<i class="fa fa-check"></i></span><br>
                <span class="text">Costing</span>
            </a>
        </li>
    </ul>
</div>

<div class="bk_tabs">
    <div id="tab1" class="bk_tab active">
        <?php include_once("package_tab1.php"); ?>
    </div>
    <div id="tab2" class="bk_tab">
        <?php include_once("travelling_tab2.php"); ?>
    </div>
    <div id="tab_daywise" class="bk_tab">
        <?php include_once("dayswise_tab3.php"); ?>
    </div>
    <div id="tab3" class="bk_tab">
        <?php include_once("costing_tab4.php"); ?>
    </div>
</div>

      <!-- </div>  
    </div>
  </div>
</div> -->

<script src="../../js/master.js"></script>
<script>
// $('#save_modal1').modal('show');

$('#airline_name1,#airline_name-1').select2();
$('#train_from_location1,#train_to_location1').select2({minimumInputLength: 1});

$('#plane_from_location1,#plane_to_location1,#train_to_location1,#airline_name1,#plane_from_location-1,#plane_to_location-1,#airline_name-1').select2();

</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>