<?php
include '../../model/model.php';
global $currency;
$currency1 = $_POST['currency'];
$currency_id = $_POST['currency_id'];
$currency1 = ($currency1 == '0') ? $currency : $currency1;
$sq_currency= mysql_fetch_assoc(mysql_query("select default_currency from currency_name_master where id='$currency_id'"));

$sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$currency1'"));
$sq_to = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$currency_id'"));

echo $sq_to['currency_rate'].','.$sq_from['currency_rate'].','.$sq_currency['default_currency'];
?>