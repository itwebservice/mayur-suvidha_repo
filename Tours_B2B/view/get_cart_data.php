<?php
include '../../model/model.php';
$register_id = $_POST['register_id'];
$sq_reg = mysql_fetch_assoc(mysql_query("select cart_data from b2b_registration where register_id='$register_id'"));
echo json_decode($sq_reg['cart_data']);
?>