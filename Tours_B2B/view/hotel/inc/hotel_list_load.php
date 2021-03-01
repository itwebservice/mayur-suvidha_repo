<?php
include '../../../../model/model.php';
$city_id = $_POST['city_id'];
if($city_id != ''){
	$query = "select hotel_id, hotel_name from hotel_master where city_id='$city_id'";
}
else{
	$query = "select hotel_id, hotel_name from hotel_master where 1";
}
$sq_hotel = mysql_query($query);
?>
<option value=''>Hotel Name</option>
<?php
while($row_hotel = mysql_fetch_assoc($sq_hotel)){
?>
	<option value="<?php echo $row_hotel['hotel_id'] ?>"><?php echo $row_hotel['hotel_name'] ?></option>
<?php } ?>