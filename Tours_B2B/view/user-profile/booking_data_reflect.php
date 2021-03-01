<?php
include_once("../../../model/model.php");
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$customer_id = $_POST['customer_id'];
$array_s = array();
$temp_arr = array();
$footer_data = array();
$financial_year_id = $_SESSION['financial_year_id'];
global $currency;
$sq_to = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$currency'"));
$to_currency_rate = $sq_to['currency_rate'];

$query = "select * from b2b_booking_master where customer_id='$customer_id' ";
if($from_date!="" && $to_date !=""){
    $from_date = date('Y-m-d H:i:s', strtotime($from_date));
	$to_date1 = date('Y-m-d H:i:s', strtotime($to_date));
	$query .=" and (created_at>='$from_date' and created_at<='$to_date1') ";
}
$query .= " order by booking_id desc";	
$count = 0;
$net_total = 0;
$sq_customer = mysql_query($query);
while($row_customer = mysql_fetch_assoc($sq_customer)){
	$hotel_total = 0;
	$transfer_total = 0;
	$activity_total = 0;
	$tours_total = 0;
	$servie_total = 0;
    $yr = explode("-", get_datetime_db($row_customer['created_at']));
    $sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_customer[customer_id]'"));
    $cart_checkout_data = json_decode($row_customer['cart_checkout_data']);
    for($i=0;$i<sizeof($cart_checkout_data);$i++){
        if($cart_checkout_data[$i]->service->name == 'Hotel'){
            $tax_arr = explode(',',$cart_checkout_data[$i]->service->hotel_arr->tax);
			$tax_amount = 0;
            for($j=0;$j<sizeof($cart_checkout_data[$i]->service->item_arr);$j++){
                $room_types = explode('-',$cart_checkout_data[$i]->service->item_arr[$j]);
                $room_cost = $room_types[2];
				$h_currency_id = $room_types[3];
                
				$tax_arr1 = explode('+',$tax_arr[0]);
				for($t=0;$t<sizeof($tax_arr1);$t++){
				  if($tax_arr1[$t]!=''){
					$tax_arr2 = explode(':',$tax_arr1[$t]);
					if($tax_arr2[2] == "Percentage"){
					  $tax_amount = $tax_amount + ($room_cost * $tax_arr2[1] / 100);
					}else{
					  $tax_amount = $tax_amount + ($room_cost +$tax_arr2[1]);
					}
				  }
				}
                $total_amount = $room_cost + $tax_amount;
            
				//Convert into default currency
				$sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
				$from_currency_rate = $sq_from['currency_rate'];
                $total_amount = ($from_currency_rate / $to_currency_rate * $total_amount);
                
                $hotel_total += $total_amount;
            }
        }
		if($cart_checkout_data[$i]->service->name == 'Transfer'){
			for($j=0;$j<sizeof($cart_checkout_data[$i]->service);$j++){
				$tax_amount = 0;
                $tax_arr = explode(',',$cart_checkout_data[$i]->service->service_arr[0]->taxation);
                $transfer_cost = explode('-',$cart_checkout_data[$i]->service->service_arr[$j]->transfer_cost);
				$room_cost = $transfer_cost[0];
				$h_currency_id = $transfer_cost[1];
				
				$tax_arr1 = explode('+',$tax_arr[0]);
				for($t=0;$t<sizeof($tax_arr1);$t++){
					if($tax_arr1[$t]!=''){
						$tax_arr2 = explode(':',$tax_arr1[$t]);
						if($tax_arr2[2] == "Percentage"){
							$tax_amount = $tax_amount + ($room_cost * $tax_arr2[1] / 100);
						}else{
							$tax_amount = $tax_amount + ($room_cost +$tax_arr2[1]);
						}
					}
				}
				$total_amount = $room_cost + $tax_amount;

				//Convert into default currency
				$sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
				$from_currency_rate = $sq_from['currency_rate'];
				$total_amount = ($from_currency_rate / $to_currency_rate * $total_amount);
			
				$transfer_total += $total_amount;
			}
		}
		if($cart_checkout_data[$i]->service->name == 'Activity'){
			for($j=0;$j<sizeof($cart_checkout_data[$i]->service);$j++){
				$tax_amount = 0;
			$tax_arr = explode(',',$cart_checkout_data[$i]->service->service_arr[$j]->taxation);
			$transfer_cost = explode('-',$cart_checkout_data[$i]->service->service_arr[$j]->transfer_type);
				$room_cost = $transfer_cost[1];
				$h_currency_id = $transfer_cost[2];
				
				$tax_arr1 = explode('+',$tax_arr[0]);
				for($t=0;$t<sizeof($tax_arr1);$t++){
				  if($tax_arr1[$t]!=''){
					$tax_arr2 = explode(':',$tax_arr1[$t]);
					if($tax_arr2[2] === "Percentage"){
					  $tax_amount = $tax_amount + ($room_cost * $tax_arr2[1] / 100);
					}else{
					  $tax_amount = $tax_amount + ($room_cost + $tax_arr2[1]);
					}
				  }
				}
				$total_amount = $room_cost + $tax_amount;

				//Convert into default currency
				$sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
				$from_currency_rate = $sq_from['currency_rate'];
				$total_amount = ($from_currency_rate / $to_currency_rate * $total_amount);
			
				$activity_total += $total_amount;
			}
		}
		if($cart_checkout_data[$i]->service->name == 'Combo Tours'){
			for($j=0;$j<sizeof($cart_checkout_data[$i]->service);$j++){
			
				$tax_amount = 0;
			    $taxation_arr = explode(',',$cart_checkout_data[$i]->service->service_arr[$j]->taxation);
				$room_cost = $cart_checkout_data[$i]->service->service_arr[$j]->total_cost;
				$h_currency_id = $cart_checkout_data[$i]->service->service_arr[$j]->currency_id;
				
				$tax_arr1 = explode('+',$taxation_arr[0]);
				for($t=0;$t<sizeof($tax_arr1);$t++){
				  if($tax_arr1[$t]!=''){
					$tax_arr2 = explode(':',$tax_arr1[$t]);
					if($tax_arr2[2] == "Percentage"){
					  $tax_amount = $tax_amount + ($room_cost * $tax_arr2[1] / 100);
					}else{
					  $tax_amount = $tax_amount + ($room_cost +$tax_arr2[1]);
					}
				  }
				}
				$total_amount = $room_cost + $tax_amount;

				//Convert into default currency
				$sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
				$from_currency_rate = $sq_from['currency_rate'];
				$total_amount = ($from_currency_rate / $to_currency_rate * $total_amount);
			
				$tours_total += $total_amount;
			}
		}
    }
    if($row_customer['coupon_code'] != ''){
        $sq_coupon = mysql_fetch_assoc(mysql_query("select offer,offer_amount from hotel_offers_tarrif where coupon_code='$row_customer[coupon_code]'"));
        if($sq_coupon['offer']=="Flat"){
           $hotel_total = $hotel_total - $sq_coupon['offer_amount'];
        }else{
            $hotel_total = $hotel_total - ($hotel_total*$sq_coupon['offer_amount']/100);
        }
    }
	$servie_total = $servie_total + $hotel_total + $transfer_total + $activity_total + $tours_total;
	$net_total += $servie_total;
    
    $sq_payment_info = mysql_fetch_assoc(mysql_query("SELECT sum(payment_amount) as sum from b2b_payment_master where booking_id='$row_customer[booking_id]' and (clearance_status!='Pending' or clearance_status!='Cancelled')"));
    $payment_amount = $sq_payment_info['sum'];
    $paid_amount +=$sq_payment_info['sum'];
    $invoice_no = get_b2b_booking_id($row_customer['booking_id'],$yr[0]);	
	$service_url = BASE_URL."model/app_settings/print_html/voucher_html/b2b_voucher.php?booking_id=$row_customer[booking_id]";
	$voucher_button = (number_format($payment_amount,2) >= number_format($servie_total,2)) ? '<button data-toggle="tooltip" style="margin-left:5px" onclick="loadOtherPage(\''. $service_url .'\')" class="btn btn-info btn-sm" title="Generate Service Voucher"><i class="fa fa-print"></i></button>' : '';
    $temp_arr = array( "data" => array (
        (int)(++$count),
         $invoice_no,
         get_date_user($row_customer['created_at']),
         number_format($servie_total,2),
         number_format($payment_amount,2),
        '<button class="btn btn-info btn-sm" onclick="booking_view_modal('.$row_customer[booking_id].')" title="View"><i class="fa fa-eye"></i></button>'.$voucher_button), "bg" => ""
    );
    array_push($array_s,$temp_arr); 
}
$footer_data = array("footer_data" => array(
	'total_footers' => 5,
	'foot0' => '',
	'col0' => 2,
	'namecol0' => "",
	'foot1' => '',
	'col1' => 1,
	'namecol1' => "Total",
	'foot2' => number_format($net_total,2),
	'col2' => 1,
	'namecol2' => "",
	'foot3' => number_format($paid_amount,2),
	'col3' => 1,
	'namecol3' => "",
	'foot4' => number_format($net_total - $paid_amount,2),
	'col4' => 1,
	'namecol4' => ""
	)
);
array_push($array_s, $footer_data);
echo json_encode($array_s);
?>