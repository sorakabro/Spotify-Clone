<?php 
    ob_start();
    session_start();

    $timezone = date_default_timezone_set("Europe/London");

    // Server database connection

   // $con = mysqli_connect("shareddb-i.hosting.stackcp.net", "spotify_clone-37379f5a", "3)xj£;#>qDdX", "spotify_clone-37379f5a");


    // Localhost database connection

    $con = mysqli_connect("localhost", "root", "mysql", "spotify_clone");

    if(mysqli_connect_errno()) {
        echo "Failed to connect:" . mysqli_connect_errno();
    }

?>