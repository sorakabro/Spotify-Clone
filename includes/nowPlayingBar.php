<?php 

// Connection to database with query for songs and order random 10 to show and save it in array

$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

$resultArray = array();

// Songquery array and push it into the result with the row id

while($row = mysqli_fetch_array($songQuery)) {
    array_push($resultArray, $row['id']);
}

// Encode Data ResultArray to jsonArray to send it as object in javascript

$jsonArray = json_encode($resultArray);
?>

<script>

//Onload page show currentplaylist, audio, track from song and volumeSoundBar with max volume

$(document).ready(function() {
    var newPlayList = <?php echo $jsonArray; ?>;
    audioElement = new Audio();
    setTrack(newPlayList[0], newPlayList, false);
    updateVolumeProgressBar(audioElement.audio);

    //Take away so when you use progressbar or volumebar not everything is getting selected (highlighted)
    $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e) {
        e.preventDefault();
    });


    $(".playBackBar .progressBar").mousedown(function() {
        mouseDown = true;
    });

    $(".playBackBar .progressBar").mousemove(function(e) {
        if(mouseDown == true) {
            //Set time of song, depending on position of the mouse
            timeFromOffset(e, this);
        }
    });

    $(".playBackBar .progressBar").mouseup(function(e) {
        timeFromOffset(e, this);
    });


     // Volume Bar 
    $(".volumeBar .progressBar").mousedown(function() {
        mouseDown = true;
    });

    $(".volumeBar .progressBar").mousemove(function(e) {
        if(mouseDown == true) {
           
            var percentage = e.offsetX / $(this).width();
            if(percentage >= 0 && percentage <= 1) {
            audioElement.audio.volume = percentage;
        }
        }
    });

    $(".volumeBar .progressBar").mouseup(function(e) {
        var percentage = e.offsetX / $(this).width();
           
            if(percentage >= 0 && percentage <= 1) {
            audioElement.audio.volume = percentage;
        }
    });

    $(document).mouseup(function(){
        mouseDown = false;
    });

});

// Function for progressbar

function timeFromOffset(mouse, progressBar) {
    var percentage = mouse.offsetX / $(progressBar).width() * 100;
    var seconds = audioElement.audio.duration * (percentage / 100);
    audioElement.setTime(seconds);
}

function prevSong() {
    if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
        audioElement.setTime(0);
    }
    else {
        currentIndex = currentIndex - 1;
        setTrack(currentPlayList[currentIndex], currentPlayList, true);
    }
}

// Function for nextSong if length of playlist is -1 go to first song = 0, otherwise just increase by +1

function nextSong() {

    if(repeat == true) {
        audioElement.setTime(0);
        playSong();
        return;
    }

    if(currentIndex == currentPlayList.length -1) {
        currentIndex = 0;
    }
    else {
        currentIndex++;
    }

    var trackToPlay = shuffle ? shufflePlayList[currentIndex] : currentPlayList[currentIndex];
    setTrack(trackToPlay, currentPlayList, true);
}

//function to repeat and change picture if its repeated or not

function setRepeat() {
    repeat = !repeat;
    var imageName = repeat ? "repeat-active.png" : "repeat.png";
    $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
}

//function to mute volume and change picture if volume is muted or not

function setMute() {
    audioElement.audio.muted = !audioElement.audio.muted;
    var imageName =  audioElement.audio.muted ? "volume-mute.png" : "volume.png";
    $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
}

//Function shuffle

function setShuffle() {
    shuffle = !shuffle;
    var imageName =  shuffle ? "shuffle-active.png" : "shuffle.png";
    $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);

    if(shuffle == true) {
        //Randomize playlist
        shuffleArray(shufflePlayList);
        currentIndex = shufflePlayList.indexOf(audioElement.currentlyPlaying.id);

    }
    else {
        //shuffle has been deactivated 
        //go back to regular playlist
        currentIndex = currentPlayList.indexOf(audioElement.currentlyPlaying.id);
    }
}

function shuffleArray(a) {
    var j, x, i;
    for (i = a.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = a[i];
        a[i] = a[j];
        a[j] = x;
    }
    return a;
}


// Function for finding trackId, Title, Artist, Path with json
function setTrack(trackId, newPlayList, play) {

    //Logic for setting up 1 currentPlayList and 1 shufflePlayList that have the same data
    if(newPlayList != currentPlayList) {
        currentPlayList = newPlayList;
        shufflePlayList = currentPlayList.slice();
        shuffleArray(shufflePlayList);
    }

    if(shuffle == true) {
        currentIndex = shufflePlayList.indexOf(trackId);
    }
    else {
        currentIndex = currentPlayList.indexOf(trackId);
    }
    pauseSong();
    
    $.post("includes/handlers/ajax/getSongJson.php", { songId: trackId}, function(data) {
        
        var track = JSON.parse(data);
        
        $(".trackName span").text(track.title);
        
        $.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist}, function(data) {
            var artist = JSON.parse(data);
            $(".trackInfo .artistName span").text(artist.name);
            $(".trackInfo .artistName span").attr("onclick", "openPage('artist.php?id=" + artist.id + "')");
        });

        $.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album}, function(data) {
            var album = JSON.parse(data);
            $(".content .albumLink img").attr("src", album.artworkPath);
            $(".content .albumLink img").attr("onclick", "openPage('album.php?id=" + album.id + "')");
            $(".trackInfo .trackName span").attr("onclick", "openPage('album.php?id=" + album.id + "')");
        });
        
        audioElement.setTrack(track);

        if(play == true) {
            playSong();
        }
    });

}

// Function playSong / pauseSong with Ajax

function playSong() {

    if(audioElement.audio.currentTime == 0) {
        $.post("includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id });
    }
    

    $(".controlButton.play").hide();
    $(".controlButton.pause").show();
    audioElement.play();
}

function pauseSong() {
    $(".controlButton.play").show();
    $(".controlButton.pause").hide();
    audioElement.pause();
}


</script>






<div id="nowPlayingBarContainer">


        <div id="nowPlayingBar">

        <div id="nowPlayingLeft">
            <div class="content">
                <span class="albumLink">
                    <img src="" alt="" class="albumArtwork">
                </span>

                <div class="trackInfo">
                    <span class="trackName">
                        <span></span>
                    </span>
                    <span class="artistName">
                        <span></span>
                    </span>
                </div>
            </div>

        </div>


        <div id="nowPlayingCenter">

        <div class="content playerControls">

        <div class="buttons">

            <button class="controlButton shuffle" title="Shuffle button" onClick="setShuffle()">
                <img src="assets/images/icons/shuffle.png" alt="Shuffle">
            </button>

            <button class="controlButton previous" title="Previous button" onClick="prevSong()">
                <img src="assets/images/icons/previous.png" alt="Previous">
            </button>

            <button class="controlButton play" title="Play button" onClick="playSong()">
                <img src="assets/images/icons/play.png" alt="Play">
            </button>

            <button class="controlButton pause" title="Pause button" style="display:none;" onClick="pauseSong()">
                <img src="assets/images/icons/pause.png" alt="Pause">
            </button>

            <button class="controlButton next" title="Next button" onClick="nextSong()">
                <img src="assets/images/icons/next.png" alt="Next">
            </button>

            <button class="controlButton repeat" title="Repeat button" onClick="setRepeat()">
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

            <button class="controlButton volume" title="Volume Button" onClick="setMute()">
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