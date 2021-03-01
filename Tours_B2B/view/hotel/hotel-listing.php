<?php
include '../../../model/model.php';
include '../../layouts/header.php';
$b2b_agent_code = $_SESSION['b2b_agent_code'];

//Object created to get room types result
include 'inc/get_final_rooms_result.php';
$get_result = new result_master;

$hotel_array = json_decode($_SESSION['hotel_array']);
$city_id = ($hotel_array[0]->city_id);
$hotel_id = ($hotel_array[0]->hotel_id);
$check_indate = $hotel_array[0]->check_indate;
$check_outdate = $hotel_array[0]->check_outdate;
$star_category_arr = $hotel_array[0]->star_category_arr;
$dynamic_room_count = ($hotel_array[0]->dynamic_room_count);

$final_rooms_arr = $hotel_array[0]->final_arr;
$nationality = $hotel_array[0]->nationality;
$final_rooms_arr = json_decode($final_rooms_arr,true);
for ($n = 0; $n < sizeof($final_rooms_arr); $n++) {
  $adults_count = ($adults_count) + ($final_rooms_arr[$n]['rooms']['adults']);
  $child_count = ($child_count) + ($final_rooms_arr[$n]['rooms']['child']);
}
$check_indate1 = date('d M Y', strtotime($check_indate));
$check_outdate1 = date('d M Y', strtotime($check_outdate));
$star_category_arr = implode(',',$star_category_arr);
//City Search
if($city_id!=''){
  $sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$city_id'"));
  $city_name = $sq_city['city_name'];
  $query = "select * from hotel_master where city_id='$city_id'";
}
//Hotel Search
if($hotel_id!=''){
  $sq_hotel = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$hotel_id'"));
  $hotel_name = $sq_hotel['hotel_name'];
  $query = "select * from hotel_master where hotel_id='$hotel_id'";
}
//Star Category filter
if($star_category_arr!=''){
  $query .= " and rating_star in($star_category_arr) ";
}
//Page Title
if($hotel_id!=''){
  $page_title = 'Results for '.$hotel_name;
}else{
  $page_title = 'Hotels in '.$city_name;
}
//Array of Check-in and Check-out date
$checkDate_array = array();
$check_in = strtotime($check_indate);
$check_out = strtotime($check_outdate);
for ($i_date=$check_in; $i_date<=$check_out; $i_date+=86400) {
    array_push($checkDate_array,date("Y-m-d", $i_date));  
}
?>
<input type='hidden' id='check_indate' value='<?=$check_indate?>'/>
<input type='hidden' id='check_outdate' value='<?=$check_outdate?>'/>
<input type='hidden' id='rooms' value='<?=$dynamic_room_count?>'/>
      <!-- ********** Component :: Page Title ********** -->
      <div class="c-pageTitleSect">
        <div class="container">
          <div class="row">
            <div class="col-md-7 col-12">

              <!-- *** Search Head **** -->
              <div class="searchHeading">
                <span class="pageTitle"><?= $page_title ?></span>

                <div class="clearfix">

                  <div class="sortSection">
                    <span class="sortTitle st-search">
                      <i class="icon it itours-timetable"></i>
                      Check In: <strong><?= $check_indate1 ?></strong>
                    </span>
                  </div>

                  <div class="sortSection">
                    <span class="sortTitle st-search">
                      <i class="icon it itours-timetable"></i>
                      Check Out: <strong><?= $check_outdate1 ?></strong>
                    </span>
                  </div>

                  <div class="sortSection">
                    <span class="sortTitle st-search">
                      <i class="icon it itours-person"></i>
                      <?php echo $adults_count; echo ($adults_count <= '1') ? ' Adult' : ' Adults'; ?>, <?php echo $child_count;  echo ($child_count <= '1') ? ' Child' : ' Children'; ?> 
                    </span>
                  </div>

                </div>

                <div class="clearfix">

                  <div class="sortSection">
                    <span class="sortTitle">
                      <i class="icon it itours-sort-1"></i>
                      Sort Hotels by:
                    </span>
                    <div class="dropdown selectable">
                      <button class="btn-dd dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Most Popular
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" onClick="get_price_filter_data('hotel_results_array','1','fromRange_cost','toRange_cost');">Price - High to Low</a>
                        <a class="dropdown-item" onClick="get_price_filter_data('hotel_results_array','2','fromRange_cost','toRange_cost');">Price - Low to High</a>
                      </div>
                    </div>
                  </div>

                  <div class="sortSection">
                    <span class="sortTitle st-search">
                      <i class="icon it itours-search"></i>
                      <span>Showing <span class="results_count"></span></span>
                    </span>
                  </div>

                </div>
              </div>
              <!-- *** Search Head End **** -->
            </div>

            <div class="col-md-5 col-12 c-breadcrumbs">
              <ul>
                <li>
                  <a href="<?= $b2b_index_url ?>">Home</a>
                </li>
                <li class="st-active">
                  <a href="javascript:void(0)">Hotel Search Result</a>
                </li>
              </ul>
            </div>

          </div>
        </div>
      </div>
      <!-- ********** Component :: Page Title End ********** -->

      <!-- ********** Component :: Hotel Listing  ********** -->
      <div class="c-containerDark">
        <div class="container">
           <!-- ********** Component :: Modify Filter  ********** -->
           <div class="row c-modifyFilter">
                <div class="col">
                  <!-- Modified Search Filter -->
                  <div class="accordion c-accordion" id="modifySearch_filter">
                  <div class="card">

                    <div class="card-header" id="headingThree">
                      <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#jsModifySearch_filter" aria-expanded="false" aria-controls="jsModifySearch_filter">
                      Modify Search >> <?php $room_word = (sizeof($final_rooms_arr)<=1)?' Room':' Rooms';?><span class="results_count"></span><?php echo ' available for '.($adults_count+$child_count).' Pax '.' in '.sizeof($final_rooms_arr).$room_word;?>
                      </button>
                    </div>
                    <div id="jsModifySearch_filter" class="collapse" aria-labelledby="jsModifySearch_filter" data-parent="#modifySearch_filter">
                      <div class="card-body">
                        <form id="frm_hotel_search">
                        <input type='hidden' id='page_type' value='hotel_listing_page' name='hotel_listing_page'/>
                          <div class="row">
                                <!-- *** City Name *** -->
                                <div class="col-md-3 col-sm-6 col-12">
                                  <div class="form-group">
                                    <label>Enter City</label>
                                    <div class="c-select2DD">
                                      <select id='hotel_city_filter' class="full-width js-roomCount" onchange="hotel_names_load(this.id);">
                                      <?php if($city_id!=''){
                                        $sq_city_name = mysql_fetch_assoc(mysql_query("select city_id, city_name from city_master where city_id='$city_id'"));?>
                                        <option value="<?php echo $sq_city_name['city_id'] ?>"><?php echo $sq_city_name['city_name'] ?></option>
                                      <?php  } ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                <!-- *** City Name End *** -->
                                <!-- *** Hotel Name *** -->
                                <div class="col-md-3 col-sm-6 col-12">
                                  <div class="form-group">
                                    <label>Enter Hotel Name</label>
                                    <div class="c-select2DD">
                                      <select id='hotel_name_filter' class="full-width js-roomCount">
                                      <?php if($hotel_id !=''){
                                        $sq_filter = mysql_fetch_assoc(mysql_query("select hotel_id,hotel_name from hotel_master where hotel_id='$hotel_id'")); ?>
                                        <option value="<?php echo $sq_filter['hotel_id'] ?>"><?php echo $sq_filter['hotel_name'] ?></option>
                                        <option value="">Hotel Name</option>
                                        <?php }
                                        else{
                                          if($city_id!=''){
                                            $querys = "select hotel_id, hotel_name from hotel_master where city_id='$city_id'"; }
                                          else{
                                            $querys = "select hotel_id, hotel_name from hotel_master where 1"; } ?>
                                          <option value="">Hotel Name</option>
                                          <?php
                                          $sq_hotels = mysql_query($querys);
                                          while($row_hotels = mysql_fetch_assoc($sq_hotels)){ ?>
                                          <option value="<?php echo $row_hotels['hotel_id'] ?>"><?php echo $row_hotels['hotel_name'] ?></option>
                                          <?php }
                                        }  ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                <!-- *** Hotel Name End *** -->
                                
                                <!-- *** Check in Date *** -->
                                <div class="col-md-3 col-sm-6 col-12">
                                  <div class="form-group">
                                    <label>*Check In</label>
                                    <div class="datepicker-wrap">
                                      <input type="text" name="date_from" class="input-text full-width" value="<?= $check_indate ?>" placeholder="mm/dd/yy" id="checkInDate" onchange='get_to_date("checkInDate","checkOutDate")' required/>
                                    </div>
                                  </div>
                                </div>
                                <!-- *** Check in Date End *** -->
                                
                                <!-- *** Check Out Date *** -->
                                <div class="col-md-3 col-sm-6 col-12">
                                  <div class="form-group">
                                    <label>*Check Out
                                      <span class="nytCount" id='total_stay'></span>
                                    </label>
                                    <div class="datepicker-wrap">
                                      <input type="text" name="date_from" class="input-text full-width" value="<?= $check_outdate ?>" placeholder="mm/dd/yy" id="checkOutDate" onchange='total_nights_reflect();' required/>
                                    </div>
                                  </div>
                                </div>
                                <!-- *** Check Out Date End *** -->
                                <div class="col-md-3 col-sm-6 col-12">
                                  <div class="form-group clearfix">
                                    <label>Hotel Star Catagory</label>
                                      <div class="form-check form-check-inline c-checkGroup">
                                        <?php $checked_status1 = (strpos($star_category_arr, "1 Star"))?'checked':'';?>
                                      <input class="form-check-input" type="checkbox" id="c1" value="1" name='star_category' <?= $checked_status1 ?>>
                                      <label class="form-check-label" role="button" for="c1">
                                        1 <i class="icon-star"></i>
                                      </label>
                                    </div>
                                      <div class="form-check form-check-inline c-checkGroup">
                                        <?php
                                        $checked_status2 =(strpos($star_category_arr, "2 Star"))?'checked':'';?>
                                      <input class="form-check-input" type="checkbox" id="c2" value="2" name='star_category' <?= $checked_status2 ?>>
                                      <label class="form-check-label" role="button" for="c2">
                                        2 <i class="icon-star"></i>
                                      </label>
                                    </div>
                                      <div class="form-check form-check-inline c-checkGroup">
                                        <?php
                                        $checked_status3 =(strpos($star_category_arr, "3 Star"))?'checked':'';?>
                                      <input class="form-check-input" type="checkbox" id="c3" value="3" name='star_category' <?= $checked_status3 ?>>
                                      <label class="form-check-label" role="button" for="c3">
                                        3<i class="icon-star"></i>
                                      </label>
                                    </div>
                                      <div class="form-check form-check-inline c-checkGroup">
                                        <?php
                                        $checked_status4 =(strpos($star_category_arr, "4 Star"))?'checked':'';?>
                                      <input class="form-check-input" type="checkbox" id="c4" value="4" name='star_category' <?= $checked_status4 ?>>
                                      <label class="form-check-label" role="button" for="c4">
                                        4 <i class="icon-star"></i>
                                      </label>
                                    </div>
                                      <div class="form-check form-check-inline c-checkGroup">
                                        <?php
                                        $checked_status5 = (strpos($star_category_arr, "5 Star"))?'checked':'';?>
                                      <input class="form-check-input" type="checkbox" id="c5" value="5" name='star_category' <?= $checked_status5 ?>>
                                      <label class="form-check-label" role="button" for="c5">
                                        5<i class="icon-star"></i>
                                      </label>
                                    </div>
                                      <div class="form-check form-check-inline c-checkGroup">
                                        <?php
                                        $checked_status7 = (strpos($star_category_arr, "7 Star"))?'checked':'';?>
                                      <input class="form-check-input" type="checkbox" id="c7" value="7" name='star_category' <?= $checked_status7 ?>>
                                      <label class="form-check-label" role="button" for="c7">
                                        7<i class="icon-star"></i>
                                      </label>
                                    </div>
                                  </div>
                                </div>

                            <div class='block blue' id='display_addRooms_modal'></div>
                            <!-- *** Add Rooms *** -->
                            <div class="col-md-3 col-sm-6 col-12">
                              <div class="form-group">
                                <label>Add Rooms</label>
                                <div class="c-addRoom">
                                  <a class="roomInfo" onclick='display_addRooms_modal()'>
                                    <strong id='total_pax'><?php echo ($adults_count + $child_count) ?></strong> Person in
                                    <strong id='room_count'><?php echo sizeof($final_rooms_arr) ?> <?= $room_word ?></strong>
                                  </a>
                                </div>
                              </div>
                            </div>
                            <!-- *** Nationality *** -->
                            <div class="col-md-3 col-sm-6 col-12">
                              <div class="form-group">
                                <label>Nationality</label>
                                <div class="c-select2DD">
                                  <select name="nationality" id='nationality' class="full-width js-roomCount">
                                    <option value="AL">India</option>
                                    <option value="WY">Dubai</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <!-- *** Nationality End *** -->
                            <input type='hidden' id='adult_count' name='adult_count'/>
                            <input type='hidden' id='child_count' name='child_count'/>
                            <input type='hidden' value='1' id='dynamic_room_count' name='dynamic_room_count'/>
                            <!-- *** Search Rooms *** -->
                            <div class="col-md-3 col-sm-6 col-12">
                            <button class="c-button lg colGrn m20-top">
                                <i class="icon itours-search"></i> SEARCH NOW
                            </button>
                            </div>
                            <!-- *** Search Rooms End *** -->
                            </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  </div>
                 <!-- Modified Search Filter End -->
          </div>
          </div>
          <div class="row">
            <!-- ***** Hotel Listing Filter ***** -->
            <div class="col-md-3 col-sm-12">

                  <!-- ***** Price Filter ***** -->
                  <div class="accordion c-accordion" id="filterPrice">
                  <div class="card">

                    <div class="card-header">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#jsFilterPrice" aria-expanded="true" aria-controls="jsFilterPrice">
                          Price Range :(<span class='currency-icon'></span>)
                        </button>
                    </div>
                    <div id="jsFilterPrice" class="collapse show" aria-labelledby="jsFilterPrice" data-parent="#filterPrice">
                        <div class="card-body">
                          <div class="c-priceRange">
                            <input type="hidden" class="slider-input" data-step="1"/>
                          </div>
                        </div>
                      </div>
                  </div>
                  </div>
                  <!-- ***** Price Filter End ***** -->

                  <!-- ***** Rating Filter ***** -->
                  <div class="accordion c-accordion" id="filterRating">
                  <div class="card">

                    <div class="card-header">
                      <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#jsFilterRating" aria-expanded="true" aria-controls="jsFilterRating">
                        User Rating
                      </button>
                    </div>

                    <div id="jsFilterRating" class="collapse show" aria-labelledby="jsFilterRating" data-parent="#filterRating">
                      <div class="card-body filters-container">
                        <ul class="c-checkSquare">
                          <li>
                            <button class="filterCheckbox">
                              <div class="c-starRating r-5">
                                <span class="stars"></span>
                              </div>
                            </button>
                          </li>
                          <li>
                            <button class="filterCheckbox">
                              <div class="c-starRating r-4">
                                <span class="stars"></span>
                              </div>
                            </button>
                          </li>
                          <li>
                            <button class="filterCheckbox">
                              <div class="c-starRating r-3">
                                <span class="stars"></span>
                              </div>
                            </button>
                          </li>
                          <li>
                            <button class="filterCheckbox">
                              <div class="c-starRating r-2">
                                <span class="stars"></span>
                              </div>
                            </button>
                          </li>
                          <li>
                            <button class="filterCheckbox">
                              <div class="c-starRating r-1">
                                <span class="stars"></span>
                              </div>
                            </button>
                          </li>
                      </ul>

                      </div>
                    </div>
                  </div>
                  </div>
                  <!-- ***** Rating Filter End ***** -->

            </div>
            <!-- ***** Hotel Listing Filter End ***** --> 
            <!-- ***** Hotel Listing ***** -->
            <div class="col-md-9 col-sm-12">
            <?php
            $actual_ccosts_array = array();
            $hotel_results_array = array();
            $price_filter_array = array();
            $sq_query = mysql_query($query);
            while(($row_query  = mysql_fetch_assoc($sq_query))){
              //Single Hotel Image
              $sq_singleImage = mysql_fetch_assoc(mysql_query("select * from hotel_vendor_images_entries where hotel_id='$row_query[hotel_id]'"));
              if($sq_singleImage['hotel_pic_url']!=''){
                $image = $sq_singleImage['hotel_pic_url'];
                $newUrl1 = preg_replace('/(\/+)/','/',$image);
                $newUrl1 = explode('uploads', $newUrl1);
                $newUrl = BASE_URL.'uploads'.$newUrl1[1];
              }else{
                $newUrl = BASE_URL.'images/dummy-image.jpg';
              }
              //Category
              $star_category = explode(' ', $row_query['rating_star']);
              $star_category = (sizeof($star_category) > 1) ? $star_category[0] : '';
              //Amenities
              $amenity = explode(',', $row_query['amenities']);
              $cost_arr = array();
              //Fetch Hotel Tariff for Room Categories(Room Types)
              $sq_tariff_master = mysql_query("select * from hotel_vendor_price_master where 1 and hotel_id='$row_query[hotel_id]'");
              while($row_tariff_master = mysql_fetch_assoc($sq_tariff_master)){
                $currency_id = $row_tariff_master['currency_id'];
                for($i_date=0; $i_date<sizeof($checkDate_array)-1; $i_date++){
            
                  $day = date("l", strtotime($checkDate_array[$i_date]));
                  $blackdated_count = mysql_num_rows(mysql_query("select * from hotel_blackdated_tarrif where pricing_id='$row_tariff_master[pricing_id]' and (from_date <='$checkDate_array[$i_date]' and to_date>='$checkDate_array[$i_date]')"));
                  $weekenddated_count = mysql_num_rows(mysql_query("select * from hotel_weekend_tarrif where pricing_id='$row_tariff_master[pricing_id]' and day='$day'"));
                  $contracted_count = mysql_num_rows(mysql_query("select * from hotel_contracted_tarrif where pricing_id='$row_tariff_master[pricing_id]' and (from_date <='$checkDate_array[$i_date]' and to_date>='$checkDate_array[$i_date]')"));
                  //################### Black-dated rates ########################//
                  if($blackdated_count>0){
                        
                      $sq_tariff = mysql_query("select * from hotel_blackdated_tarrif where pricing_id='$row_tariff_master[pricing_id]' and (from_date <='$checkDate_array[$i_date]' and to_date>='$checkDate_array[$i_date]') ");
                      while($row_tariff = mysql_fetch_assoc($sq_tariff)){
                          //No.of rooms loop
                          for($i_room=0;$i_room<sizeof($final_rooms_arr);$i_room++){
                            $roomChild_arr = array();
                            $total_pax = $final_rooms_arr[$i_room]['rooms']['adults'] + $final_rooms_arr[$i_room]['rooms']['child'];
                            if($row_tariff['max_occupancy']>=$total_pax){
                              
                              //For Extra bed for more than 2 adults
                              $extra_bed_cost = ($final_rooms_arr[$i_room]['rooms']['adults'] >2) ? $row_tariff['extra_bed'] : '0';
                              //Child Age-wise costing
                              for($k=0;$k<sizeof($final_rooms_arr[$i_room]['rooms']);$k++){
                                if($final_rooms_arr[$i_room]['rooms']['childAge'][$k] >= $row_query['cwb_from'] && $final_rooms_arr[$i_room]['rooms']['childAge'][$k] <= $row_query['cwb_to']){
                                  array_push($roomChild_arr,intval($row_tariff['child_with_bed']));
                                }
                                elseif($final_rooms_arr[$i_room]['rooms']['childAge'][$k] >= $row_query['cwob_from'] && $final_rooms_arr[$i_room]['rooms']['childAge'][$k] <= $row_query['cwob_to']){
                                  array_push($roomChild_arr,intval($row_tariff['child_without_bed']));
                                }
                                else{
                                  array_push($roomChild_arr,intval(0));
                                }
                              }
                              if($row_tariff['markup_per']!='0'){
                                $markup_type = 'Percentage';
                                $markup_amount = $row_tariff['markup_per'];
                              }else{
                                $markup_type = 'flat';
                                $markup_amount = $row_tariff['markup'];
                              }
                              //Checking discount applied or not
                              $sq_offers_count = mysql_num_rows(mysql_query("select * from hotel_offers_tarrif where (from_date <='$checkDate_array[$i_date]' and to_date>='$checkDate_array[$i_date]' and hotel_id='$row_query[hotel_id]')"));
                              if($sq_offers_count>0){
                                $row_offers = mysql_fetch_assoc(mysql_query("select * from hotel_offers_tarrif where (from_date <='$checkDate_array[$i_date]' and to_date>='$checkDate_array[$i_date]' and hotel_id='$row_query[hotel_id]')"));
                                $offer_type = $row_offers['type'];
                                $offer_in = $row_offers['offer'];
                                $offer_amount = $row_offers['offer_amount'];
                                $coupon_code = $row_offers['coupon_code'];
                                $agent_type = $row_offers['agent_type'];
                              }
                              else{
                                $offer_type = '';
                                $offer_in = '';
                                $offer_amount = 0;
                                $coupon_code = '';
                                $agent_type = '';
                              }

                              $cost_arr1 = array( 
                                'rooms' => array(
                                            "room_count"=> $final_rooms_arr[$i_room]['rooms']['room'], 
                                            "check_date"=>$checkDate_array[$i_date],
                                            "category"=>$row_tariff['room_category'], 
                                            "room_cost"=>intval($row_tariff['double_bed']),
                                            "child_cost"=>$roomChild_arr,
                                            "extra_bed_cost"=> intval($extra_bed_cost),
                                            "max_occupancy"=>$row_tariff['max_occupancy'],
                                            "markup_type"=>$markup_type,
                                            "markup_amount"=>floatval($markup_amount),
                                            "offer_type"=>$offer_type,
                                            "offer_amount"=>floatval($offer_amount),
                                            "offer_in"=>$offer_in,
                                            "coupon_code"=>$coupon_code,
                                            "agent_type"=>$agent_type,
                                            "currency_id"=>$currency_id
                                )
                              );
                              array_push($cost_arr,$cost_arr1);
                            }
                          }
                      } // Rates while loop End
                  } //Black dated rates If loop End
                  //################# Black-dated rates End #####################//

                  //################### Weekend-dated rates ########################//
                  if($weekenddated_count>0){
                        
                    $sq_tariff = mysql_query("select * from hotel_weekend_tarrif where pricing_id='$row_tariff_master[pricing_id]' and day='$day'");
                    while($row_tariff = mysql_fetch_assoc($sq_tariff)){
                        //No.of rooms loop
                        for($i_room=0;$i_room<sizeof($final_rooms_arr);$i_room++){
                          $roomChild_arr = array();
                          $total_pax = $final_rooms_arr[$i_room]['rooms']['adults'] + $final_rooms_arr[$i_room]['rooms']['child'];
                          if($row_tariff['max_occupancy']>=$total_pax){
                            
                            //For Extra bed for more than 2 adults
                            $extra_bed_cost = ($final_rooms_arr[$i_room]['rooms']['adults'] >2) ? $row_tariff['extra_bed'] : '0';

                            //Child Age-wise costing
                            for($k=0;$k<sizeof($final_rooms_arr[$i_room]['rooms']);$k++){
                              if($final_rooms_arr[$i_room]['rooms']['childAge'][$k] >= $row_query['cwb_from'] && $final_rooms_arr[$i_room]['rooms']['childAge'][$k] <= $row_query['cwb_to']){
                                array_push($roomChild_arr,intval($row_tariff['child_with_bed']));
                              }
                              elseif($final_rooms_arr[$i_room]['rooms']['childAge'][$k] >= $row_query['cwob_from'] && $final_rooms_arr[$i_room]['rooms']['childAge'][$k] <= $row_query['cwob_to']){
                                array_push($roomChild_arr,intval($row_tariff['child_without_bed']));
                              }
                              else{
                                array_push($roomChild_arr,intval(0));
                              }
                            }
                            if($row_tariff['markup_per']!=0){
                              $markup_type = 'Percentage';
                              $markup_amount = $row_tariff['markup_per'];
                            }else{
                              $markup_type = 'flat';
                              $markup_amount = $row_tariff['markup'];
                            }
                            //Checking discount applied or not
                            $sq_offers_count = mysql_num_rows(mysql_query("select * from hotel_offers_tarrif where (from_date <='$checkDate_array[$i_date]' and to_date>='$checkDate_array[$i_date]') and hotel_id='$row_query[hotel_id]'"));
                            if($sq_offers_count>0){
                              $row_offers = mysql_fetch_assoc(mysql_query("select * from hotel_offers_tarrif where (from_date <='$checkDate_array[$i_date]' and to_date>='$checkDate_array[$i_date]') and hotel_id='$row_query[hotel_id]'"));
                                $offer_type = $row_offers['type'];
                                $offer_in = $row_offers['offer'];
                                $offer_amount = $row_offers['offer_amount'];
                                $coupon_code = $row_offers['coupon_code'];
                                $agent_type = $row_offers['agent_type'];
                              }else{
                                $offer_type = '';
                                $offer_in = '';
                                $offer_amount = 0;
                                $coupon_code = '';
                                $agent_type = '';
                              }
                            $cost_arr1 = array( 
                              'rooms' => array(
                                          "room_count"=> $final_rooms_arr[$i_room]['rooms']['room'], 
                                          "check_date"=>$checkDate_array[$i_date],
                                          "category"=>$row_tariff['room_category'], 
                                          "room_cost"=>intval($row_tariff['double_bed']),
                                          "child_cost"=>$roomChild_arr,
                                          "extra_bed_cost"=> intval($extra_bed_cost),
                                          "max_occupancy"=>$row_tariff['max_occupancy'],
                                          "markup_type"=>$markup_type,
                                          "markup_amount"=>floatval($markup_amount),
                                          "offer_type"=>$offer_type,
                                          "offer_amount"=>floatval($offer_amount),
                                          "offer_in"=>$offer_in,
                                          "coupon_code"=>$coupon_code,
                                          "agent_type"=>$agent_type,
                                          "currency_id"=>$currency_id
                              )
                            );
                            array_push($cost_arr,$cost_arr1);
                          }
                        }
                    } // Rates while loop End
                  } //Weekend dated rates If loop End
                  //################# Weekend-dated rates End #####################//

                  //################### Contracted-dated rates ########################//
                  if($contracted_count>0){                          
                    $sq_tariff = mysql_query("select * from hotel_contracted_tarrif where pricing_id='$row_tariff_master[pricing_id]' and (from_date <='$checkDate_array[$i_date]' and to_date>='$checkDate_array[$i_date]') ");
                    while($row_tariff = mysql_fetch_assoc($sq_tariff)){
                        //No.of rooms loop
                        for($i_room=0;$i_room<sizeof($final_rooms_arr);$i_room++){
                          $roomChild_arr = array();
                          $total_pax = $final_rooms_arr[$i_room]['rooms']['adults'] + $final_rooms_arr[$i_room]['rooms']['child'];
                          if($row_tariff['max_occupancy']>=$total_pax){
                            
                            //For Extra bed for more than 2 adults
                            $extra_bed_cost = ($final_rooms_arr[$i_room]['rooms']['adults'] >2) ? $row_tariff['extra_bed'] : '0';

                            //Child Age-wise costing
                            for($k=0;$k<sizeof($final_rooms_arr[$i_room]['rooms']);$k++){
                              if($final_rooms_arr[$i_room]['rooms']['childAge'][$k] >= $row_query['cwb_from'] && $final_rooms_arr[$i_room]['rooms']['childAge'][$k] <= $row_query['cwb_to']){
                                array_push($roomChild_arr,intval($row_tariff['child_with_bed']));
                              }
                              elseif($final_rooms_arr[$i_room]['rooms']['childAge'][$k] >= $row_query['cwob_from'] && $final_rooms_arr[$i_room]['rooms']['childAge'][$k] <= $row_query['cwob_to']){
                                array_push($roomChild_arr,intval($row_tariff['child_without_bed']));
                              }
                              else{
                                array_push($roomChild_arr,intval(0));
                              }
                            }
                            if($row_tariff['markup_per']!=0){
                              $markup_type = 'Percentage';
                              $markup_amount = $row_tariff['markup_per'];
                            }else{
                              $markup_type = 'flat';
                              $markup_amount = $row_tariff['markup'];
                            }
                            //Checking discount applied or not
                            $sq_offers_count = mysql_num_rows(mysql_query("select * from hotel_offers_tarrif where (from_date <='$checkDate_array[$i_date]' and to_date>='$checkDate_array[$i_date]') and hotel_id='$row_query[hotel_id]'"));
                            if($sq_offers_count>0){
                              $row_offers = mysql_fetch_assoc(mysql_query("select * from hotel_offers_tarrif where (from_date <='$checkDate_array[$i_date]' and to_date>='$checkDate_array[$i_date]') and hotel_id='$row_query[hotel_id]'"));
                                $offer_type = $row_offers['type'];
                                $offer_in = $row_offers['offer'];
                                $offer_amount = $row_offers['offer_amount'];
                                $coupon_code = $row_offers['coupon_code'];
                                $agent_type = $row_offers['agent_type'];
                              }else{
                                $offer_type = '';
                                $offer_in = '';
                                $offer_amount = 0;
                                $coupon_code = '';
                                $agent_type = '';
                              }
                            $cost_arr1 = array( 
                              'rooms' => array(
                                          "room_count"=> $final_rooms_arr[$i_room]['rooms']['room'], 
                                          "check_date"=>$checkDate_array[$i_date],
                                          "category"=>$row_tariff['room_category'], 
                                          "room_cost"=>intval($row_tariff['double_bed']),
                                          "child_cost"=>$roomChild_arr,
                                          "extra_bed_cost"=> intval($extra_bed_cost),
                                          "max_occupancy"=>$row_tariff['max_occupancy'],
                                          "markup_type"=>$markup_type,
                                          "markup_amount"=>floatval($markup_amount),
                                          "offer_type"=>$offer_type,
                                          "offer_amount"=>floatval($offer_amount),
                                          "offer_in"=>$offer_in,
                                          "coupon_code"=>$coupon_code,
                                          "agent_type"=>$agent_type,
                                          "currency_id"=>$currency_id
                              )
                            );
                            array_push($cost_arr,$cost_arr1);
                          }
                        }
                    } // Rates while loop End
                  } //Contracted dated rates If loop End
                //################# Contracted-dated rates End #####################//

                } //checkin Datewise for loop End
              }  //Hotel Master Rate while loop End
              //Method Call to get room types result
              $final_room_type_array = $get_result->get_result_array($cost_arr);
              //Get selected currency rate
              $currency = $_SESSION['session_currency_id'];
              $sq_to = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$currency'"));
              $to_currency_rate = $sq_to['currency_rate'];
              if(sizeof($final_room_type_array)>0){
                $last_date = sizeof($checkDate_array)-2;
                $all_costs_array = array();
                for($i=0;$i<sizeof($final_room_type_array);$i++){

                  if($final_room_type_array[$i]['check_date'] == $checkDate_array[$last_date]){
                    //Total cost
                    $room_cost = intval($final_room_type_array[$i]['room_cost']) + intval($final_room_type_array[$i]['daywise_exbcost'])+ intval($final_room_type_array[$i]['child_cost']);
                   
                    //Markup
                    if($final_room_type_array[$i]['markup_type'] == 'Percentage'){
                      $room_cost = $room_cost+($room_cost * ($final_room_type_array[$i]['markup_amount']/100));
                    }else{
                      $room_cost = $room_cost + ($final_room_type_array[$i]['markup_amount']*(sizeof($checkDate_array)-1));
                    }

                    //Offers
                    if($final_room_type_array[$i]['offer_type'] != ''){
                      if($final_room_type_array[$i]['offer_type'] == 'Offer'){
                      if($final_room_type_array[$i]['offer_in'] == 'Percentage')
                          $room_cost = $room_cost-($room_cost * ($final_room_type_array[$i]['offer_amount']/100));
                      else
                          $room_cost = $room_cost - $final_room_type_array[$i]['offer_amount'];
                      }
                    }
                    
                    //Final cost push into array
                    $room_cost = ceil($room_cost);
                    $h_currency_id = $final_room_type_array[$i]['currency_id'];
                    array_push($all_costs_array,array('amount' => $room_cost,'id'=>$h_currency_id));

                  }
                }
                $prices = $array_master->array_column($all_costs_array, 'amount');
                $min_array = $all_costs_array[array_search(min($prices), $prices)];

                array_push($price_filter_array,$min_array);

                $sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
                $from_currency_rate = $sq_from['currency_rate'];
                $c_amount = $from_currency_rate / $to_currency_rate * $min_array['amount'];
                array_push($actual_ccosts_array,$c_amount);

                $hotel_result_array = array(
                  "hotel_id"=>$row_query['hotel_id'],
                  "hotel_name"=>$row_query['hotel_name'],
                  "hotel_image"=>$newUrl,
                  "star_category"=>$star_category,
                  "hotel_address"=>addslashes($row_query['hotel_address']),
                  "hotel_type"=>$row_query['hotel_type'],
                  "meal_plan"=>$row_query['meal_plan'],
                  "amenity" =>$amenity,
                  "checkDate_array"=>$checkDate_array,
                  "final_room_type_array"=>$final_room_type_array,
                  "description"=>addslashes($row_query['description']),
                  "policies"=>addslashes($row_query['policies']),
                  "best_lowest_cost"=>array('id'=>$min_array['id'],
                                            'cost'=>$min_array['amount'])
                );
                array_push($hotel_results_array,$hotel_result_array);
              }
            }//Main Hotel While Loop End
            $array = $array_master->array_column($price_filter_array, 'amount');
            $min_array = $price_filter_array[array_search(min($array), $array)];
            $max_array = $price_filter_array[array_search(max($array), $array)];
            ?>
              <input type='hidden' value='<?= json_encode($hotel_results_array,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>' id='hotel_results_array' name='hotel_results_array'/>
              <input type='hidden' class='best-cost-currency' id='bestlow_cost' value='<?= min($actual_ccosts_array)?>'/>
              <input type='hidden' class='best-cost-currency' id='besthigh_cost' value='<?= max($actual_ccosts_array) ?>'/>
              <input type='hidden' class='best-cost-id' id='bestlow_cost' value='<?= $min_array['id'] ?>'/>
              <input type='hidden' class='best-cost-id' id='besthigh_cost' value='<?= $max_array['id']  ?>'/>
              <input type="hidden" id='price_rangevalues'/>
              <input type='hidden' id='temp_amount'/>
              
              <div id='hotel_result_block'></div>
            </div>
          </div>
        </div>
      </div>
      <!-- ********** Component :: Hotel Listing End ********** -->
<?php include '../../layouts/footer.php'; ?>
<script type="text/javascript" src="../../js/jquery.range.min.js"></script>
<script type="text/javascript" src="../../js/pagination.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL ?>Tours_B2B/view/hotel/js/index.js"></script>
<script type="text/javascript" src="js/amenities.js"></script>
<script>
$('#checkInDate, #checkOutDate').datetimepicker({ timepicker:false,format:'m/d/Y',minDate:new Date() });
total_nights_reflect();
// Get currency changed values in hotel result
function get_currency_change(currency_id,JSONItems,fromRange_cost,toRange_cost,get_price_filter_data_result){
  var base_url = $('#base_url').val();
  var final_arr = [];
  
  JSONItems.forEach(function (item){
    var currency_rates = get_currency_rates(item.best_lowest_cost.id,currency_id).split('-');
    var to_currency_rate =  currency_rates[0];
    var from_currency_rate = currency_rates[1];
    var amount = parseFloat(to_currency_rate / from_currency_rate * item.best_lowest_cost.cost).toFixed(2);
    if(compare(amount,fromRange_cost,toRange_cost)){
      final_arr.push(item);
    }
  });
  get_price_filter_data_result(final_arr);
}

// Get Hotel results data
function get_price_filter_data(hotel_results_array,type,fromRange_cost,toRange_cost){
  var base_url = $('#base_url').val();
  var selected_value = document.getElementById(hotel_results_array).value;
  var JSONItems = JSON.parse(selected_value);
  var final_arr = [];
  setTimeout(() => {
    if (typeof Storage !== 'undefined') {
      if (localStorage) {
        var currency_id = localStorage.getItem('global_currency');
      } else {
        var currency_id = window.sessionStorage.getItem('global_currency');
      }
    }
    if(type==1){
      final_arr = (JSONItems).sort(function(a,b){
        //First Value
        var currency_rates = get_currency_rates(a.best_lowest_cost.id,currency_id).split('-');
        var to_currency_rate =  currency_rates[0];
        var from_currency_rate = currency_rates[1];
        var aamount = parseFloat(to_currency_rate / from_currency_rate * a.best_lowest_cost.cost).toFixed(2);
        //Second value      
        var currency_rates = get_currency_rates(b.best_lowest_cost.id,currency_id).split('-');
        var to_currency_rate =  currency_rates[0];
        var from_currency_rate = currency_rates[1];
        var bamount = parseFloat(to_currency_rate / from_currency_rate * b.best_lowest_cost.cost).toFixed(2);

        return bamount-aamount;
      });
      get_price_filter_data_result(final_arr);
    }
    else if(type==2){
      final_arr = (JSONItems).sort(function(a,b){
        //First Value
        var currency_rates = get_currency_rates(a.best_lowest_cost.id,currency_id).split('-');
        var to_currency_rate =  currency_rates[0];
        var from_currency_rate = currency_rates[1];
        var aamount = parseFloat(to_currency_rate / from_currency_rate * a.best_lowest_cost.cost).toFixed(2);
        //Second value      
        var currency_rates = get_currency_rates(b.best_lowest_cost.id,currency_id).split('-');
        var to_currency_rate =  currency_rates[0];
        var from_currency_rate = currency_rates[1];
        var bamount = parseFloat(to_currency_rate / from_currency_rate * b.best_lowest_cost.cost).toFixed(2);

        return aamount - bamount;
      });
      get_price_filter_data_result(final_arr);
    }
    else{
      var currency_id = $('#currency').val();
      get_currency_change(currency_id,JSONItems,fromRange_cost,toRange_cost,get_price_filter_data_result);    
    }
    
  }, 800);
}
//Display Hotel results data 
function get_price_filter_data_result(final_arr){
  var base_url = $('#base_url').val();
  $.post(base_url+'Tours_B2B/view/hotel/hotel_results.php', { final_arr: final_arr }, function (data) {
    $('#hotel_result_block').html(data);
	});
}
get_price_filter_data('hotel_results_array','2','0','0');

//////////////Debounce function for range slider//////////////////////
function getSliderValue(){
  var ranges = $('.slider-input').val().split(',');
  
  $('.slider-input').attr({
    min: parseFloat(ranges[0]).toFixed(2),
    max: parseFloat(ranges[1]).toFixed(2)
  });
  if(ranges[0]!='' && ranges[1]!='' && ranges[0]!=='NaN' && ranges[1]!=='NaN'){
    get_price_filter_data('hotel_results_array','3',ranges[0],ranges[1]);
  }
}
const setSliderValue = function (fun) {
	let timer;
	return function () {
		let context = this;
		args = arguments;
		clearTimeout(timer);
		timer = setTimeout(() => {
			fun.apply(context, args);
		}, 800);
	};
};
const passSliderValue = setSliderValue(getSliderValue);
//////////////////////////////////////////////////////////////////////
//Make session for best hotel costs
clearTimeout(a);
var a = setTimeout(function() {

  var best_price_list = document.querySelectorAll(".best-cost-currency");
  var best_price_id = document.querySelectorAll(".best-cost-id");
  var bestAmount_arr = [];
  for(var i=0;i<best_price_list.length;i++){
    bestAmount_arr.push({
      'amount':best_price_list[i].value,
      'id':best_price_id[i].value});
  }
  sessionStorage.setItem('best_price_list',JSON.stringify(bestAmount_arr));
},100);

</script>