
<?php
include("../../config.php");

//Query to database taking data from song table specific songId and put it into array and json encode it.

if(isset($_POST['songId'])) {
    $songId = $_POST['songId'];

    $query = mysqli_query($con, "SELECT * FROM songs WHERE id='$songId'");

    $resultArray = mysqli_fetch_array($query);

    echo json_encode($resultArray);
}


?>