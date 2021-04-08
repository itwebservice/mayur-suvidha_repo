<?php
$flag = true;
class vendor_estimate_delete{
    public function vendor_estimate_deletefn(){
        
        $estimate_id = $_POST['estimate_id'];
        $estimateDetails = mysql_fetch_assoc(mysql_query('SELECT * FROM `vendor_estimate` WHERE `estimate_id`='.$estimate_id));
        $vendor_type = $estimateDetails['vendor_type'];
        $vendor_type_id = $estimateDetails['vendor_type_id'];
        $basic_cost = $estimateDetails['basic_cost'];
        $non_recoverable_taxes = $estimateDetails['non_recoverable_taxes'];
        $service_charge = $estimateDetails['service_charge'];
        $other_charges = $estimateDetails['other_charges'];
        $discount = $estimateDetails['discount'];
        $our_commission = $estimateDetails['our_commission'];
        $tds = $estimateDetails['tds'];
        $net_total = $estimateDetails['net_total'];
        $roundoff = $estimateDetails['roundoff'];
        $row_spec = 'purchase';
        $branch_admin_id = $estimateDetails['branch_admin_id'];
        $purchase_date1 = $estimateDetails['purchase_date'];
        $reflections = $estimateDetails['reflections'];


        global $transaction_master;

        $purchase_gl = get_vendor_purchase_gl_id($vendor_type, $vendor_type_id);
        $created_at = get_date_db($purchase_date1);
        $year1 = explode("-", $created_at);
        $yr1 =$year1[0];
        
        $supplier_amount = $basic_cost + $non_recoverable_taxes + $other_charges;
        $purchase_amount = $basic_cost + $non_recoverable_taxes + $other_charges;
        begin_t();
        //Getting supplier Ledger
        $q = "select * from ledger_master where group_sub_id='105' and customer_id='$vendor_type_id' and user_type='$vendor_type'";
        $sq_sup = mysql_fetch_assoc(mysql_query($q));
        $supplier_gl = $sq_sup['ledger_id'];
        ////////////purchase/////////////
        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = "";
        $payment_amount = $purchase_amount;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $purchase_amount, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('For',$vendor_type.' Purchase');
        $gl_id = $supplier_gl;
        $payment_side = "Debit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, '1',$payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');

        //////service charge
        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = "";
        $payment_amount = $service_charge;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $service_charge, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('For',$vendor_type.' Purchase');
        $gl_id = $supplier_gl;
        $payment_side = "Debit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, '2',$payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');

        /////////Service Charge Tax Amount////////
        // Eg. CGST:(9%):24.77, SGST:(9%):24.77
        $service_tax_subtotal = explode(',',$service_tax_subtotal);
        $tax_ledgers = explode(',',$reflections[0]->purchase_taxes);
        $total_tax = 0;
        for($i=0;$i<sizeof($service_tax_subtotal);$i++){

        $service_tax = explode(':',$service_tax_subtotal[$i]);
        $tax_amount = $service_tax[2];
        $ledger = $tax_ledgers[$i];
        $total_tax += $tax_amount;
        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = "";
        $payment_amount = $tax_amount;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1),$created_at, $tax_amount, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('For',$vendor_type.' Purchase');
        $gl_id = $ledger;
        $payment_side = "Debit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, '1',$payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');

        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = "";
        $payment_amount = $tax_amount;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1),$created_at, $tax_amount, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('For',$vendor_type.' Purchase');
        $gl_id = $ledger;
        $payment_side = "Credit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, '2',$payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');
        }
        //////Supplier Credit 
        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = "";
        $payment_amount = $total_tax;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $total_tax, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('For',$vendor_type.' Purchase');
        $gl_id = $supplier_gl;
        $payment_side = "Debit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, '3',$payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');

        ////// Discount ////////////
        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = "";
        $payment_amount = $discount;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $discount, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = 37;
        $payment_side = "Credit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');

        ////// Commision ////////////
        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = "";
        $payment_amount = $our_commission;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $our_commission, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = ($reflections[0]->purchase_commission != '') ? $reflections[0]->purchase_commission : 25;
        $payment_side = "Credit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');

        ////// Tds Payable Credit////////////
        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = "";
        $payment_amount = $tds;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $tds, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = ($reflections[0]->purchase_tds != '') ? $reflections[0]->purchase_tds : 126;
        $payment_side = "Debit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, '1',$payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');

        ////// Tds Payable Debit////////////
        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = "";
        $payment_amount = $tds;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $tds, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = ($reflections[0]->purchase_tds != '') ? $reflections[0]->purchase_tds : 126;
        $payment_side = "Credit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, '2',$payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');

        //////Supplier Credit 
        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = "";
        $payment_amount = $tds;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $tds, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('For',$vendor_type.' Purchase');
        $gl_id = $supplier_gl;
        $payment_side = "Debit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, '4',$payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');

        ////Roundoff Value
        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = "";
        $payment_amount = $roundoff;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_estimate_id($estimate_id,$yr1), $created_at, $roundoff, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('For',$vendor_type.' Purchase');
        $gl_id = 230;
        $payment_side = "Debit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');

        //Supplier
        $module_name = $vendor_type;
        $module_entry_id = $estimate_id;
        $transaction_id = $transaction_id1;
        $payment_amount = $net_total;
        $payment_date = $created_at;
        $payment_particular = get_purchase_partucular(get_vendor_payment_id($estimate_id,$yr1), $created_at, $net_total, $vendor_type, $vendor_type_id);
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $purchase_gl;
        $payment_side = "Credit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'PURCHASE');

        $sq_vendor = mysql_query("update `vendor_estimate` set status='Inactive' where estimate_id=".$estimate_id);

        if($sq_vendor){
            commit_t();
            echo "Purchase has been succesfully deleted";
        }
        else{
            rollback_t();
            echo "error--Purchase has not been deleted";
            exit;
        }
    }
}
