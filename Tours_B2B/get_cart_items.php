<?php include "../model/model.php";
$cart_list_arr = $_POST['cart_list_arr'];
if(sizeof($cart_list_arr)>0 && $cart_list_arr[0]){
    $hotel_list_arr = array();
    $transfer_list_arr = array();
    $activity_list_arr = array();
    $tours_list_arr = array();
    for($i=0;$i<sizeof($cart_list_arr);$i++){
      if($cart_list_arr[$i]['service']['name'] == 'Hotel'){
        array_push($hotel_list_arr,$cart_list_arr[$i]);
      }
      if($cart_list_arr[$i]['service']['name'] == 'Transfer'){
        array_push($transfer_list_arr,$cart_list_arr[$i]);
      }
      if($cart_list_arr[$i]['service']['name'] == 'Activity'){
        array_push($activity_list_arr,$cart_list_arr[$i]);
      }
      if($cart_list_arr[$i]['service']['name'] == 'Combo Tours'){
        array_push($tours_list_arr,$cart_list_arr[$i]);
      }
    }
    if(sizeof($hotel_list_arr) >0){
        for($i=0;$i<sizeof($hotel_list_arr);$i++){
        $hotel_id = $hotel_list_arr[$i]['service']['id'];
        $sq_hotel = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$hotel_id'")); 
        $image = ($hotel_list_arr[$i]['service']['name']=='Hotel')?'itours-skyline':'';?>
            
            <!-- ***** Cart Item ****** -->
            <div class="c-cartItem">
                <i class="icon it <?= $image ?>"></i>
                <div class="clearfix ci-header">
                <span class="ci-title"><?= stripslashes($sq_hotel['hotel_name']) ?></span>
                <div class="ci-btnRemove">
                    <button title='Remove' onclick="remove_item('<?= $hotel_list_arr[$i]['service']['uuid'] ?>')"></button>
                </div>
                </div>

            <?php
            for($j=0;$j<sizeof($hotel_list_arr[$i]['service']['item_arr']);$j++){
                $room_types = explode('-',$hotel_list_arr[$i]['service']['item_arr'][$j]);
                $room_no = $room_types[0];
                $room_cat = $room_types[1];
                $room_cost = $room_types[2];
                $h_currency = $room_types[3];
            ?>
                <!-- **** Room Type **** -->
                <div class="clearfix ci-body">
                <span class="ci-subtitle"><?= $room_no.' : '.$room_cat ?></span>
                <div class="ci-cost">
                    <span class='currency-icon'></span>
                    <span class='cart-currency-price'><?= $room_cost ?></span>
                    <span class='c-hide cart-currency-id'><?= $h_currency ?></span>
                </div>
                </div>
                <!-- **** Room Type End **** -->
            <?php } ?>
            </div>
            <!-- ***** Cart Item End ****** -->
        <?php }
    }
    if(sizeof($transfer_list_arr) >0){
        for($i=0;$i<sizeof($transfer_list_arr);$i++){

            $image = ($transfer_list_arr[$i]['service']['name']=='Transfer')?'itours-car':'';
            $room_cost_arr = explode('-',$transfer_list_arr[$i]['service']['service_arr'][0]['transfer_cost']);
            $room_cost = $room_cost_arr[0];
            $h_currency = $room_cost_arr[1];
        ?>    
            <!-- ***** Cart Item ****** -->
            <div class="c-cartItem">
                <i class="icon it <?= $image ?>"></i>
                <div class="clearfix ci-header">
                <span class="ci-title"><?= $transfer_list_arr[$i]['service']['service_arr'][0]['vehicle_name'] ?></span>
                <div class="ci-btnRemove">
                    <button title='Remove' onclick="remove_item('<?= $transfer_list_arr[$i]['service']['uuid'] ?>')"></button>
                </div>
                </div>
                <?php
                ?>
                <!-- **** Room Type **** -->
                <div class="clearfix ci-body">
                    <span class="ci-subtitle">No.Of Vehicles : <?= $transfer_list_arr[$i]['service']['service_arr'][0]['no_of_vehicles'] ?></span>
                    <div class="ci-cost">
                        <span class='currency-icon'></span>
                        <span class='cart-currency-price'><?= $room_cost ?></span>
                        <span class='c-hide cart-currency-id'><?= $h_currency ?></span>
                    </div>
                </div>
                <!-- **** Room Type End **** -->
            <?php  ?>
            </div>
            <!-- ***** Cart Item End ****** -->
    <?php }
    }
    if(sizeof($activity_list_arr) >0){
        for($i=0;$i<sizeof($activity_list_arr);$i++){
        $exc_id = $activity_list_arr[$i]['service']['id'];
        $image = ($activity_list_arr[$i]['service']['name']=='Activity')?'itours-hot-air-balloon':'';?>
            
            <!-- ***** Cart Item ****** -->
            <div class="c-cartItem">
                <i class="icon it <?= $image ?>"></i>
                <div class="clearfix ci-header">
                <span class="ci-title"><?= $activity_list_arr[$i]['service']['service_arr'][0]['act_name'] ?></span>
                <div class="ci-btnRemove">
                    <button title='Remove' onclick="remove_item('<?= $activity_list_arr[$i]['service']['uuid'] ?>')"></button>
                </div>
                </div>

            <?php
                $room_types = explode('-',$activity_list_arr[$i]['service']['service_arr'][0]['transfer_type']);
                $transfer_type = $room_types[0];
                $room_cost = $room_types[1];
                $h_currency = $room_types[2];
            ?>
                <!-- **** Room Type **** -->
                <div class="clearfix ci-body">
                <span class="ci-subtitle"><?= $transfer_type ?></span>
                <div class="ci-cost">
                    <span class='currency-icon'></span>
                    <span class='cart-currency-price'><?= $room_cost ?></span>
                    <span class='c-hide cart-currency-id'><?= $h_currency ?></span>
                </div>
                </div>
                <!-- **** Room Type End **** -->
            </div>
            <!-- ***** Cart Item End ****** -->
        <?php }
    }
    
    if(sizeof($tours_list_arr) >0){
        for($i=0;$i<sizeof($tours_list_arr);$i++){
        $exc_id = $tours_list_arr[$i]['service']['id'];
        $image = ($tours_list_arr[$i]['service']['name']=='Combo Tours')?'itours-sun-umbrella':'';?>
            
            <!-- ***** Cart Item ****** -->
            <div class="c-cartItem">
                <i class="icon it <?= $image ?>"></i>
                <div class="clearfix ci-header">
                <span class="ci-title"><?= $tours_list_arr[$i]['service']['service_arr'][0]['package'] ?></span>
                <div class="ci-btnRemove">
                    <button title='Remove' onclick="remove_item('<?= $tours_list_arr[$i]['service']['uuid'] ?>')"></button>
                </div>
                </div>

                <!-- **** Room Type **** -->
                <div class="clearfix ci-body">
                <span class="ci-subtitle"><?= $tours_list_arr[$i]['service']['service_arr'][0]['nights'].'N/'.$tours_list_arr[$i]['service']['service_arr'][0]['days'].'D' ?></span>
                <div class="ci-cost">
                    <span class='currency-icon'></span>
                    <span class='cart-currency-price'><?= $tours_list_arr[$i]['service']['service_arr'][0]['total_cost'] ?></span>
                    <span class='c-hide cart-currency-id'><?= $tours_list_arr[$i]['service']['service_arr'][0]['currency_id'] ?></span>
                </div>
                </div>
                <!-- **** Room Type End **** -->
            </div>
            <!-- ***** Cart Item End ****** -->
        <?php }
    } ?>
<div class="clearfix text-right">
    <button class="c-button md" onclick='proceed_to_checkout();'>Proceed</button>
</div>
<?php }
else{ ?>
<span>Your Shopping Cart Is Empty!</span>
<?php } ?>
<script>
var cart_list = document.querySelectorAll(".cart-currency-price");
var cart_list_id = document.querySelectorAll(".cart-currency-id");

var cart_amount_arr = [];
for(var i=0;i<cart_list.length;i++){
    cart_amount_arr.push({
    'amount':cart_list[i].innerHTML,
    'id':cart_list_id[i].innerHTML});
}
localStorage.setItem('cart_item_list',JSON.stringify(cart_amount_arr));

currency_converter();
</script>
