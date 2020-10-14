<?php
include("../../config.php");

//Query to database taking data from album specific albumId and put it into array and json encode it.

if(isset($_POST['albumId'])) {
    $albumId = $_POST['albumId'];

    $query = mysqli_query($con, "SELECT * FROM albums WHERE id='$albumId'");

    $resultArray = mysqli_fetch_array($query);

    echo json_encode($resultArray);
}


?>