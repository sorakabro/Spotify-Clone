<?php 
    ob_start();

    $timezone = date_default_timezone_set("Europe/London");

    $con = mysqli_connect("localhost", "root", "mysql", "spotify_clone");

    if(mysqli_connect_errno()) {
        echo "Failed to connecct:" .mysqli_connect_errno();
    }

?>