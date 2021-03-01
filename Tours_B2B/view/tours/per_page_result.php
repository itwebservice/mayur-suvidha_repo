<?php
include '../../../model/model.php';
$tours_result_array= $_POST['data'];
$coupon_list_arr = array();
$coupon_info_arr= array();
if(sizeof($tours_result_array)>0){
   for($i=0;$i<sizeof($tours_result_array);$i++){
    $h_currency_id = $tours_result_array[$i]['currency_id'];
?>
  <input type="hidden" id="taxation-<?=$tours_result_array[$i]['package_id']?>" value='<?= $tours_result_array[$i]['taxation'][0]['taxation_type'].'-'.$tours_result_array[$i]['taxation'][0]['service_tax']?>'>
  <!-- ***** Tours Card ***** -->
  <div class="c-cardList type-hotel">
    <div class="c-cardListTable">
      <!-- *** Tours Card image *** -->
      <div class="cardList-image">
        <img src="<?= $tours_result_array[$i]['image']?>" alt="iTours" />
        <input type="hidden" value="<?= $tours_result_array[$i]['image'] ?>" id="image-<?= $tours_result_array[$i]['package_id'] ?>"/>
        <div class="typeOverlay">
        </div>
      </div>
      <!-- *** Tours Card image End *** -->

      <!-- *** Tours Card Info *** -->
      <div class="cardList-info" role="button">
        <button class="expandSect">View Details</button>
        <div class="dividerSection type-1 noborder">
          <div class="divider s1" role="button" data-toggle="collapse" href="#collapseExample<?= $tours_result_array[$i]['package_id']?>"
            aria-expanded="false" aria-controls="collapseExample">
            <h4 class="cardTitle"><span id="package-<?= $tours_result_array[$i]['package_id'] ?>"><?= $tours_result_array[$i]['package_name']?></span>
            <span class="c-tag" id="package_code-<?= $tours_result_array[$i]['package_id'] ?>"><?= $tours_result_array[$i]['package_code']?></span>
            </h4>

            <div class="infoSection">
              <span class="cardInfoLine">
                <?= $tours_result_array[$i]['dest_name']?>
              </span>
            </div>

            <div class="infoSection">
              <span class="cardInfoLine cust">
                <i class="icon it itours-calendar"></i>
                <?= $tours_result_array[$i]['total_nights']?> Nights <?= $tours_result_array[$i]['total_days']?> Days
                <input type="hidden" value="<?= $tours_result_array[$i]['total_nights'].'-'.$tours_result_array[$i]['total_days'] ?>" id="days-<?= $tours_result_array[$i]['package_id'] ?>"/>
              </span>
            </div>

          </div>

          <div class="divider s2">
            <div class="priceTag">
              <div class="p-old">
                <span class="o_lbl">Total Price</span>
                <span class="price_main">
                  <span class="p_currency currency-icon"></span>
                  <span class="p_cost tours-currency-price" id="total_cost-<?= $tours_result_array[$i]['package_id'] ?>"><?= $tours_result_array[$i]['total_cost'] ?></span>
                  <span class="c-hide tours-currency-id" id="h_currency_id-<?= $tours_result_array[$i]['package_id'] ?>"><?= $h_currency_id ?></span>
                </span>
                <small>(exclusive of all taxes)</small>
              </div>
            </div>
            <button class="c-button md" onclick="add_to_cart('<?= $tours_result_array[$i]['package_id'] ?>','Tours')"><i class="icon it itours-shopping-cart"></i> Add To Cart</button>
          </div>
        </div>


      </div>
      <!-- *** Tours Card Info End *** -->
    </div>

    <!-- *** Tours Details Accordian *** -->
    <div class="collapse" id="collapseExample<?= $tours_result_array[$i]['package_id']?>">
      <div class="cardList-accordian">
        <!-- ***** Tours Info Tabs ***** -->
        <div class="c-compTabs">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="costing-tab" data-toggle="tab" href="#costing-tab<?= $tours_result_array[$i]['package_id']?>" role="tab"
                aria-controls="costing" aria-selected="true">Costing</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="itinerary-tab" data-toggle="tab" href="#itinerary-tab<?= $tours_result_array[$i]['package_id']?>" role="tab"
                aria-controls="itinerary" aria-selected="true">Itinerary</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="travel-tab" data-toggle="tab" href="#travel-tab<?= $tours_result_array[$i]['package_id']?>" role="tab"
                aria-controls="travel" aria-selected="true">Travel & Stay</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" id="inclusion-tab" data-toggle="tab" href="#inclusion-tab<?= $tours_result_array[$i]['package_id']?>" role="tab"
                aria-controls="inclusion" aria-selected="true">Inclusion</a>
            </li>
          </ul>

          <div class="tab-content" id="myTabContent">
            <!-- **** Tab costing **** -->
            <div class="tab-pane fade show active" id="costing-tab<?= $tours_result_array[$i]['package_id']?>" role="tabpanel" aria-labelledby="costing-tab">

              <!-- **** Policies List **** -->
              <div class="clearfix m20-btm">
                <div class="row">
                  <div class="col-12">
                    <div class="c-flexCards">

                      <div class="f_card">
                        <span class="currency_icon currency-icon"></span>
                        <span class="currency_amount adult_cost-currency-price"><?= $tours_result_array[$i]['adult_cost'] ?></span>
                        <span class="c-hide adult-currency-id"><?= $h_currency_id ?></span>
                        <span class="currency_for">For Adult(PP)</span>
                      </div>

                      <?php if($tours_result_array[$i]['child_without']!='0'){ ?>
                      <div class="f_card">
                        <span class="currency_icon currency-icon"></span>
                        <span class="currency_amount childwo_cost-currency-price"><?= $tours_result_array[$i]['child_without'] ?></span>
                        <span class="c-hide childwo-currency-id"><?= $h_currency_id ?></span>
                        <span class="currency_for">For ChildWithout Bed(PP)</span>
                      </div>
                      <?php } ?>

                      <?php if($tours_result_array[$i]['child_with']!='0'){ ?>
                      <div class="f_card">
                        <span class="currency_icon currency-icon"></span>
                        <span class="currency_amount childwi_cost-currency-price"><?= $tours_result_array[$i]['child_with'] ?></span>
                        <span class="c-hide childwi-currency-id"><?= $h_currency_id ?></span>
                        <span class="currency_for">For ChildWith Bed(PP)</span>
                      </div>
                      <?php } ?>

                      <?php if($tours_result_array[$i]['extra_bed']!='0'){ ?>
                      <div class="f_card">
                        <span class="currency_icon currency-icon"></span>
                        <span class="currency_amount extrabed-currency-price"><?= $tours_result_array[$i]['extra_bed'] ?></span>
                        <span class="c-hide extrabed-currency-id"><?= $h_currency_id ?></span>
                        <span class="currency_for">Extra Bed</span>
                      </div>
                      <?php } ?>

                      <?php if($tours_result_array[$i]['infant_cost']!='0'){ ?>
                      <div class="f_card">
                        <span class="currency_icon currency-icon"></span>
                        <span class="currency_amount infant_cost-currency-price"><?= $tours_result_array[$i]['infant_cost'] ?></span>
                        <span class="c-hide infant-currency-id"><?= $h_currency_id ?></span>
                        <span class="currency_for">For Infant(PP)</span>
                      </div>
                      <?php } ?>

                    </div>
                  </div>
                </div>

              </div>
              <!-- **** Policies List End **** -->

            </div>
            <!-- **** Tab costing End **** -->

            <!-- **** Tab itenary **** -->
            <div class="tab-pane fade" id="itinerary-tab<?= $tours_result_array[$i]['package_id']?>" role="tabpanel" aria-labelledby="itinerary-tab">

              <!-- **** Day Info List **** -->
              <div class="c-cardListInfo">
                <div class="cardListInfo-row">
                  <!-- **** List **** -->
                  <?php for($pi=0;$pi<sizeof($tours_result_array[$i]['program_array']);$pi++){ ?>
                    <div class="ListInfo-col">

                      <div class="dayCount">
                        <span class="s1">DAY</span>
                        <span class="s2"><?= ($pi+1) ?></span>
                      </div>

                      <div class="dayInfo">
                        <h5 class="h1"><?= $tours_result_array[$i]['program_array'][$pi]['attraction'] ?></h5>
                        <span class="staticText">
                          <?= $tours_result_array[$i]['program_array'][$pi]['day_wise_program'] ?>
                        </span>
                        <div class="itemList">
                          <span class="item">
                            <i class="icon it itours-bed"></i>
                            <?= $tours_result_array[$i]['program_array'][$pi]['stay'] ?>
                          </span>
                          <?php if($tours_result_array[$i]['program_array'][$pi]['meal_plan']!=''){ ?>
                            <span class="item">
                              <i class="icon it itours-cutlery"></i>
                              <?= $tours_result_array[$i]['program_array'][$pi]['meal_plan'] ?>
                            </span>
                          <?php } ?>
                        </div>
                      </div>

                    </div>
                  <?php } ?>
                  <!-- **** List End **** -->
                </div>
              </div>
              <!-- **** Day Info List **** -->
            </div>
            <!-- **** Tab itenary End **** -->

            <!-- **** Tab Tours Car **** -->
            <div class="tab-pane fade" id="travel-tab<?= $tours_result_array[$i]['package_id']?>" role="tabpanel" aria-labelledby="travel-tab">
                <!-- **** Tab Hotel Car **** -->
                  <div class="clearfix m20-btm">
                    <div class="row">
                      <div class="col-12 m20-btm">
                        <h3 class="c-heading">
                          Hotel Details
                        </h3>
                        <?php
                        for($hotel_i = 0;$hotel_i < sizeof($tours_result_array[$i]['hotels_array']);$hotel_i++){
                          ?>
                        <!-- *** Hotel Card Info *** -->
                        <div class="c-cardListHolder">
                          <div class="c-cardListTable type-3">

                            <div class="cardList-info">
                              <div class="flexGrid">
                                <div class="gridItem">
                                  <div class="infoCard">
                                    <span class="infoCard_price"><?= $tours_result_array[$i]['hotels_array'][$hotel_i]['hotel']?></span>
                                    <span class="infoCard_data"><?= $tours_result_array[$i]['hotels_array'][$hotel_i]['city']?></span>
                                  </div>
                                </div>

                                <div class="gridItem">
                                  <div class="infoCard c-halfText m0">
                                    <span class="infoCard_label">Hotel Type</span>
                                    <span class="infoCard_price"><?= $tours_result_array[$i]['hotels_array'][$hotel_i]['hotel_type']?></span>
                                  </div>
                                </div>

                                <div class="gridItem styleForMobile M-m0">
                                  <div class="infoCard m5-btm">
                                    <span class="infoCard_label">Stay Duration</span>
                                    <span class="infoCard_price"><?= $tours_result_array[$i]['hotels_array'][$hotel_i]['nights']?> Nights</span>
                                  </div>
                                </div>
                              </div>
                            </div>

                          </div>
                        </div>
                        <!-- *** Hotel Card Info End *** -->
                        <?php } ?>

                      </div>

                      <?php
                      if(sizeof($tours_result_array[$i]['transport_array'])>0){ ?>
                      <div class="col-12 m20-btm">
                        <h3 class="c-heading">
                          Transport Details
                        </h3>
                        <?php
                        for($tr_i = 0;$tr_i < sizeof($tours_result_array[$i]['transport_array']);$tr_i++){ ?>
                            <!-- *** Transport Card Info *** -->
                            <div class="c-cardListHolder type-1">
                              <div class="infoCard">
                                <span class="infoCard_label">Vehicle name</span>
                                <span class="infoCard_price"><?= $tours_result_array[$i]['transport_array'][$tr_i]['vehicle']?></span>
                              </div>
                            </div>
                            <!-- *** Transport Card Info End *** -->
                        <?php } ?>
                      </div>
                      <?php } ?>
                    </div>

                  </div>
                <!-- **** Tab Hotel Car End **** -->
            </div>
            <!-- **** Tab Tours Car End **** -->
            <!-- **** Tab Policies **** -->
            <div class="tab-pane fade" id="inclusion-tab<?= $tours_result_array[$i]['package_id']?>" role="tabpanel" aria-labelledby="inclusion-tab">
              <!-- **** Policies List **** -->
              <div class="clearfix margin20-bottom">
                <?php if($tours_result_array[$i]['inclusions'] != ''){?>
                <h3 class="c-heading">
                  Inclusions
                </h3>
                <div class="custom_texteditor">
                    <?= $tours_result_array[$i]['inclusions']?>
                </div>
                <?php } ?>
                <?php if($tours_result_array[$i]['inclusions'] != ''){?>
                <h3 class="c-heading">
                  Exclusions
                </h3>
                <div class="custom_texteditor">
                <?= $tours_result_array[$i]['exclusions']?>
                </div>
                <?php } ?>
                <?php if($tours_result_array[$i]['terms_condition'] != ''){?>
                <h3 class="c-heading">
                  Terms & Consitions
                </h3>
                <div class="custom_texteditor">
                <?= $tours_result_array[$i]['terms_condition']?>
                </div>
                <?php } ?>
              </div>
              <!-- **** Policies List End **** -->
            </div>
            <!-- **** Tab Policies End **** -->


          </div>
        </div>
        <!-- ***** Tours Info Tabs End***** -->
      </div>
    </div>
    <!-- *** Tours Details Accordian End *** -->
  </div>
  <!-- ***** Tours Card End ***** -->
            
    <?php
    }
} //Activity arrays for loop
?>
<script>
$(document).ready(function () {

    clearTimeout(b);
    var b = setTimeout(function() {
           
      var amount_list = document.querySelectorAll(".tours-currency-price");
      var amount_id = document.querySelectorAll(".tours-currency-id");
       
      var adult_price_list = document.querySelectorAll(".adult_cost-currency-price");
      var adult_price_cid = document.querySelectorAll(".adult-currency-id");

      var childwo_price_list = document.querySelectorAll(".childwo_cost-currency-price");
      var childwo_price_cid = document.querySelectorAll(".childwo-currency-id");

      var childwi_price_list = document.querySelectorAll(".childwi_cost-currency-price");
      var childwi_price_cid = document.querySelectorAll(".childwi-currency-id");

      var extrabed_price_list = document.querySelectorAll(".extrabed-currency-price");
      var extrabed_price_id = document.querySelectorAll(".extrabed-currency-id");
      
      var infant_price_list = document.querySelectorAll(".infant_cost-currency-price");
      var infant_price_id = document.querySelectorAll(".infant-currency-id");

      //Tours Best Cost
      var amount_arr = [];
      for(var i=0;i<amount_list.length;i++){
        amount_arr.push({
            'amount':amount_list[i].innerHTML,
            'id':amount_id[i].innerHTML});
      }
      sessionStorage.setItem('tours_amount_list',JSON.stringify(amount_arr));

      //Adult cost prices
      var roomAmount_arr = [];
      for(var i=0;i<adult_price_list.length;i++){
        roomAmount_arr.push({
            'amount':adult_price_list[i].innerHTML,
            'id':adult_price_cid[i].innerHTML});
      }
      sessionStorage.setItem('adult_price_list',JSON.stringify(roomAmount_arr));

      //Child Wo cost prices
      var roomAmount_arr = [];
      for(var i=0;i<childwo_price_list.length;i++){
        roomAmount_arr.push({
            'amount':childwo_price_list[i].innerHTML,
            'id':childwo_price_cid[i].innerHTML});
      }
      sessionStorage.setItem('childwo_price_list',JSON.stringify(roomAmount_arr));

      //Child WI cost prices
      var roomAmount_arr = [];
      for(var i=0;i<childwi_price_list.length;i++){
        roomAmount_arr.push({
            'amount':childwi_price_list[i].innerHTML,
            'id':childwi_price_cid[i].innerHTML});
      }
      sessionStorage.setItem('childwi_price_list',JSON.stringify(roomAmount_arr));

      //Extra Bed Cost
      var offerAmount_arr = [];
      for(var i=0;i<extrabed_price_list.length;i++){
        offerAmount_arr.push({
            'amount':extrabed_price_list[i].innerHTML,
            'id':extrabed_price_id[i].innerHTML});
      }
      sessionStorage.setItem('extrabed_price_list',JSON.stringify(offerAmount_arr));

      //Infant Cost
      var offerAmount_arr = [];
      for(var i=0;i<infant_price_list.length;i++){
        offerAmount_arr.push({
            'amount':infant_price_list[i].innerHTML,
            'id':infant_price_id[i].innerHTML});
      }
      sessionStorage.setItem('infant_price_list',JSON.stringify(offerAmount_arr));
      tours_page_currencies();
    },500);
});
</script>