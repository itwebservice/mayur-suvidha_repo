<?php 
include_once('../../../../model/model.php');
include_once('../../../../model/vendor/dashboard/vendor_estimate_delete.php');
include_once('../../../../model/app_settings/transaction_master.php');
include_once('../../../../view/vendor/inc/vendor_generic_functions.php');

$vendor_estimate_delete = new vendor_estimate_delete;
$vendor_estimate_delete->vendor_estimate_deletefn();
?>