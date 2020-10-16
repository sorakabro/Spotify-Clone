<?php
include("../../config.php");

//Query to database taking data from artist table specific artistId and put it into array and json encode it.

if(isset($_POST['artistId'])) {
	$artistId = $_POST['artistId'];

	$query = mysqli_query($con, "SELECT * FROM artists WHERE id='$artistId'");

	$resultArray = mysqli_fetch_array($query);

	echo json_encode($resultArray);
}
?>