<?php
include "../../../model/model.php"; 
include "../../../model/group_tour/booking/booking_save.php";

$whatsapp_send = new booking_save();
$whatsapp_send->whatsapp_send();
?>