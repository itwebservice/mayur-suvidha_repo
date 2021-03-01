<?php
class result_master{
    function get_result_array($cost_arr){
        //Sort complete result array by room-count-wise and dates-wise
        usort($cost_arr, function($a, $b){
            if ($a["rooms"] == $b["rooms"])
                return (0);
            return (($a["rooms"] < $b["rooms"]) ? -1 : 1);
        });

        //Get Sepearte Room-count-wise hotel category costing
        $final_result_array = array();
        for($i=0;$i<sizeof($cost_arr);$i++){
            $total_cost = 0;
            $child_cost = 0;
            for($j=$i+1;$j<=$i+1;$j++){
                if($cost_arr[$i]['rooms']['category'] == $cost_arr[$j]['rooms']['category'] && $cost_arr[$i]['rooms']['room_count'] == $cost_arr[$j]['rooms']['room_count']){

                    $total_cost = $cost_arr[$i]['rooms']['room_cost'];
                    
                    //Offers
                    // if($cost_arr[$i]['rooms']['offer_type'] != ''){
                    //     if($cost_arr[$i]['rooms']['offer_type'] == 'Offer'){
                    //     if($cost_arr[$i]['rooms']['offer_in'] == 'Percentage')
                    //         $total_cost = $total_cost-($total_cost * ($cost_arr[$i]['rooms']['offer_amount']/100));
                    //     else
                    //         $total_cost = $total_cost - $cost_arr[$i]['rooms']['offer_amount'];
                    //     }
                    // }
                    
                    $extrabed_cost = $cost_arr[$i]['rooms']['extra_bed_cost'];
                    for($k=0;$k<sizeof($cost_arr[$i]['rooms']);$k++){
                        $child_cost += $cost_arr[$i]['rooms']['child_cost'][$k];
                    }
                    $cost_arr1 = array(
                    'rooms' => array(
                                "room_count"=>      $cost_arr[$i]['rooms']['room_count'], 
                                "check_date"=>      $cost_arr[$i]['rooms']['check_date'],
                                "category"=>        $cost_arr[$i]['rooms']['category'], 
                                "room_cost"=>       intval($total_cost),
                                "child_cost"=>      $child_cost,
                                "extra_bed_cost"=>  intval($extrabed_cost),
                                "max_occupancy"=>   $cost_arr[$i]['rooms']['max_occupancy'],
                                "markup_type"=>     $cost_arr[$i]['rooms']['markup_type'],
                                "markup_amount"=>   $cost_arr[$i]['rooms']['markup_amount'],
                                "offer_type"=>      $cost_arr[$i]['rooms']['offer_type'],
                                "offer_amount"=>    $cost_arr[$i]['rooms']['offer_amount'],
                                "offer_in"=>        $cost_arr[$i]['rooms']['offer_in'],
                                "coupon_code"=>     $cost_arr[$i]['rooms']['coupon_code'],
                                "agent_type"=>      $cost_arr[$i]['rooms']['agent_type'],
                                "currency_id"=>     $cost_arr[$i]['rooms']['currency_id']
                    ));
                    array_push($final_result_array,$cost_arr1);
                }
                else{
                    $total_cost = $cost_arr[$i]['rooms']['room_cost'];
                    for($k=0;$k<sizeof($cost_arr[$i]['rooms']);$k++){
                        $child_cost += $cost_arr[$i]['rooms']['child_cost'][$k];
                    }
                    $cost_arr2 = array( 
                    'rooms' => array(
                                "room_count"=>      $cost_arr[$i]['rooms']['room_count'], 
                                "check_date"=>      $cost_arr[$i]['rooms']['check_date'],
                                "category"=>        $cost_arr[$i]['rooms']['category'], 
                                "room_cost"=>       intval($total_cost),
                                "child_cost"=>      $child_cost,
                                "extra_bed_cost"=>  intval($cost_arr[$i]['rooms']['extra_bed_cost']),
                                "max_occupancy"=>   $cost_arr[$i]['rooms']['max_occupancy'],
                                "markup_type"=>     $cost_arr[$i]['rooms']['markup_type'],
                                "markup_amount"=>   $cost_arr[$i]['rooms']['markup_amount'],
                                "offer_type"=>     $cost_arr[$i]['rooms']['offer_type'],
                                "offer_amount"=>   $cost_arr[$i]['rooms']['offer_amount'],
                                "offer_in"=>       $cost_arr[$i]['rooms']['offer_in'],
                                "coupon_code"=>    $cost_arr[$i]['rooms']['coupon_code'],
                                "agent_type"=>     $cost_arr[$i]['rooms']['agent_type'],
                                "currency_id"=>    $cost_arr[$i]['rooms']['currency_id']
                    )
                    );
                    array_push($final_result_array,$cost_arr2);
                }
            } //j For Loop
        } //i For Loop
        //Category Array and Room-count array for next-step array creation
        $result_category_array = array();
        $room_array = array();
        for($i=0;$i<sizeof($final_result_array);$i++){
            array_push($result_category_array,$final_result_array[$i]['rooms']['category']);
            array_push($room_array,$final_result_array[$i]['rooms']['room_count']);
        }

        //Array for same room-count and same category but different dates
        $category_array = array();
        for($k=0;$k<sizeof($result_category_array);$k++){
            $final_result_array1 = array(); 
            for($i=0;$i<sizeof($final_result_array);$i++){
                if($final_result_array[$i]['rooms']['category'] === $result_category_array[$k] && $final_result_array[$i]['rooms']['room_count'] == $room_array[$k]){
                array_push($final_result_array1,$final_result_array[$i]['rooms']);
                }
            }
            array_push($category_array,$final_result_array1);
        }
        //Final categorywise costing array prpareation
        $final_category_array = array();
        for($i=0;$i<sizeof($category_array);$i++){
            $daywise_acost = 0;
            $daywise_ccost = 0;  
            $daywise_exbcost = 0;                            
            for($j=0;$j<sizeof($category_array[$i]);$j++){
                $daywise_acost += $category_array[$i][$j]['room_cost'];
                $daywise_ccost += $category_array[$i][$j]['child_cost'];
                $daywise_exbcost += $category_array[$i][$j]['extra_bed_cost'];

                $categorywise_array = array(
                'room_count'=>      $category_array[$i][$j]['room_count'],
                'category'=>        $category_array[$i][$j]['category'],
                'check_date'=>      $category_array[$i][$j]['check_date'],
                'room_cost' =>      $daywise_acost,
                'child_cost'=>      $daywise_ccost,
                'daywise_exbcost'=> $daywise_exbcost,
                "max_occupancy"=>   $category_array[$i][$j]['max_occupancy'],
                "markup_type"=>     $category_array[$i][$j]['markup_type'],
                "markup_amount"=>   $category_array[$i][$j]['markup_amount'],
                "offer_type"=>      $category_array[$i][$j]['offer_type'],
                "offer_amount"=>    $category_array[$i][$j]['offer_amount'],
                "offer_in"=>        $category_array[$i][$j]['offer_in'],
                "coupon_code"=>     $category_array[$i][$j]['coupon_code'],
                "agent_type"=>      $category_array[$i][$j]['agent_type'],
                "currency_id"=>     $category_array[$i][$j]['currency_id']
                );
            }
            array_push($final_category_array,$categorywise_array);
        }

        //Find Dupicate array's key's and uset them
        $final_room_type_array = array();
        $duplicate_keys = array();
        $tmp = array();       
        $keep_key_assoc = false;
        foreach ($final_category_array as $key => $val){
            // convert objects to arrays, in_array() does not support objects
            if (is_object($val))
                $val = (array)$val;
            if (!in_array($val, $tmp)){
                $tmp[] = $val;
            }
            else
                $duplicate_keys[] = $key;                
        }
        foreach ($duplicate_keys as $key)
            unset($final_category_array[$key]);
        ////////////////////////////////////////////////

        $final_room_type_array = $keep_key_assoc ? $final_category_array : array_values($final_category_array);
        return $final_room_type_array;
    }
}
?>