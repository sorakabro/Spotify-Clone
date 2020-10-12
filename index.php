<?php 

include("includes/config.php");

// session_destroy();

if(isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = $_SESSION['userLoggedIn'];
}

else {
    header("Location: register.php");
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to spotify clone!</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>


    <div id="mainContainer">

        <div id="topContainer">

            <div id="navBarContainer">
                <nav class="navBar">
                    <a href="index.php" class="logo">
                        <img src="assets/images/icons/logospotifyclone.png" alt="logo">
                    </a>


                    <div class="group">
                        <div class="navItem">
                            <a href="search.php" class="navItemLink">Search
                            <img src="assets/images/icons/search.png" class="icon" alt="search icon">
                            </a>
                        </div>
                    </div>

                <div class="group">
                        <div class="navItem">
                            <a href="browse.php" class="navItemLink">Browse</a>
                        </div>

                        <div class="navItem">
                            <a href="yourMusic.php" class="navItemLink">Your Music</a>
                        </div>

                        <div class="navItem">
                            <a href="profile.php" class="navItemLink">Benjamin</a>
                        </div>
                 </div>




                </nav>
            </div>


        </div>


            <div id="nowPlayingBarContainer">


        <div id="nowPlayingBar">

        <div id="nowPlayingLeft">
            <div class="content">
                <span class="albumLink">
                    <img src="https://jooinn.com/images/square-4.jpg" alt="" class="albumArtwork">
                </span>

                <div class="trackInfo">
                    <span class="trackName">
                        <span>Hellow</span>
                    </span>
                    <span class="artistName">
                        <span>Artist Name</span>
                    </span>
                </div>
            </div>

        </div>


        <div id="nowPlayingCenter">

        <div class="content playerControls">

        <div class="buttons">

            <button class="controlButton shuffle" title="Shuffle button">
                <img src="assets/images/icons/shuffle.png" alt="Shuffle">
            </button>

            <button class="controlButton previous" title="Previous button">
                <img src="assets/images/icons/previous.png" alt="Previous">
            </button>

            <button class="controlButton play" title="Play button">
                <img src="assets/images/icons/play.png" alt="Play">
            </button>

            <button class="controlButton pause" title="Pause button" style="display:none;">
                <img src="assets/images/icons/pause.png" alt="Pause">
            </button>

            <button class="controlButton next" title="Next button">
                <img src="assets/images/icons/next.png" alt="Next">
            </button>

            <button class="controlButton repeat" title="Repeat button">
                <img src="assets/images/icons/repeat.png" alt="Repeat">
            </button>

            

        </div>

        <div class="playBackBar">

            <span class="progressTime current">0.00</span>

            <div class="progressBar">
                <div class="progressBarBg">
                    <div class="progress"></div>
                </div>
            </div>

            <span class="progressTime remaining">0.00</span>

        </div>




        </div>
            
        </div>


        <div id="nowPlayingRight">

        <div class="volumeBar">

            <button class="controlButton volume" title="Volume Button">
                <img src="assets/images/icons/volume.png" alt="Volume button">
            </button>

            <div class="progressBar">
                <div class="progressBarBg">
                    <div class="progress"></div>
                </div>
            </div>


        </div>
            
        </div>



        </div>

        </div>
    </div>
    



   

</body>
</html>