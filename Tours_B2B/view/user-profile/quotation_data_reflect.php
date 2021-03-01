<?php
include_once("../../../model/model.php");
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$array_s = array();
$temp_arr = array();
$footer_data = array();
$register_id = $_SESSION['register_id'];

$query = "select * from b2b_quotations where 1 and register_id = '$register_id'";
if($from_date!='' && $to_date!=""){
	$from_date = date('Y-m-d', strtotime($from_date));
	$to_date = date('Y-m-d', strtotime($to_date));
	$query .= " and created_at between '$from_date' and '$to_date' "; 
}
$query .=" order by quotation_id desc ";

$count = 1;
$array_s = array();
	$temp_arr = array();
	$quotation_cost = 0;
	$sq_quotation = mysql_query($query);
	while($row_quotation = mysql_fetch_assoc($sq_quotation)){

		$sq_customer =  mysql_fetch_assoc(mysql_query("select company_name from b2b_registration where register_id = '$row_quotation[register_id]'"));

		$cart_list_arr = $row_quotation['cart_list_arr'];
		$pdf_data_array = json_decode($row_quotation['pdf_data_array']);
		$cust_name = $pdf_data_array[0]->cust_name;
		
		$markup_in = $pdf_data_array[0]->markup_in;
		$markup_amount = $pdf_data_array[0]->markup_amount;
		$taxation_type = $pdf_data_array[0]->taxation_type;
		$taxation_id = $pdf_data_array[0]->taxation_id;
		$grand_total = $pdf_data_array[0]->grand_total;
		if($markup_in == 'Percentage'){
		  $markup = $grand_total*($markup_amount/100);
		}
		else{
		  $markup = $markup_amount;
		}
		$grand_total += $markup;
		$tax_amt = ($grand_total*($taxation_id/100));
		$quotation_cost = $grand_total + $tax_amt;
		
		$pdf_data_array = json_encode($pdf_data_array);
		$cart_list_arr = $cart_list_arr;
		$url1 = BASE_URL.'model/app_settings/print_html/quotation_html/quotation_html_2/b2b_quotation_html.php?pdf_data_array='.urlencode($pdf_data_array).'&cart_list_arr='.urlencode($cart_list_arr);

		$temp_arr = array(
			$count++,
			get_date_user($row_quotation['created_at']),
			$cust_name,
			number_format($quotation_cost,2),
			'<a style="color: white !important;" data-toggle="tooltip" onclick="loadOtherPage(\''.$url1.'\')" class="btn btn-info btn-sm" title="Download Quotation PDF"><i class="fa fa-print"></i></a>',
		  );
		array_push($array_s,$temp_arr); 
}

$footer_data = array("footer_data" => array());

array_push($array_s, $footer_data);
echo json_encode($array_s);
?>