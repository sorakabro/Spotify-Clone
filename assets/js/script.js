

var currentPlayList = [];
var shufflePlayList = [];
var tempPlayList = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;


// Hide options menu when clicked
$(document).click(function(click) {
    var target = $(click.target);

    if(!target.hasClass("item") && !target.hasClass("optionsButton")) {
        hideOptionsMenu();
    }
});

// hide options menu when scroll
$(window).scroll(function() {
    hideOptionsMenu();
});

// ADD song to playlist and hide option menu after

$(document).on("change", "select.playlist", function() {
    var select = $(this);
    var playlistId = select.val();
    var songId = select.prev(".songId").val();

    $.post("includes/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId})
    .done(function(error) {

        if(error != "") {
            alert(error);
            return;
        }

        hideOptionsMenu();
        select.val("");
    });
});

function openPage(url) {

    if(timer != null) {
        clearTimeout(timer);
    }

	if(url.indexOf("?") == -1) {
		url = url + "?";
	}

	var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
	console.log(encodedUrl);
	$("#mainContent").load(encodedUrl);
	$("body").scrollTop(0);
	history.pushState(null, null, url);
}

//Function remove from playlist

function removeFromPlaylist(button, playlistId) {
    var songId = $(button).prevAll(".songId").val();

    $.post("includes/handlers/ajax/removeFromPlaylist.php", { playlistId: playlistId, songId: songId })
		.done(function(error) {

			if(error != "") {
				alert(error);
				return;
			}

			//do something when ajax returns
			openPage("playlist.php?id=" + playlistId);
		});

}

//Function create new playlist

function createPlaylist() {
	console.log(userLoggedIn);
	var popup = prompt("Please enter the name of your playlist");

	if(popup != null) {

		$.post("includes/handlers/ajax/createPlaylist.php", { name: popup, username: userLoggedIn })
		.done(function(error) {

			if(error != "") {
				alert(error);
				return;
			}

			//do something when ajax returns
			openPage("yourMusic.php");
		});

	}

}

//Function Delete playlist

function deletePlaylist(playlistId) {

    var prompt = confirm("Are you sure you want to delete this playlist?");

    if(prompt == true) {
        

        $.post("includes/handlers/ajax/deletePlaylist.php", { playlistId: playlistId })
		.done(function(error) {

			if(error != "") {
				alert(error);
				return;
			}

			//do something when ajax returns
			openPage("yourMusic.php");
		});
    }

}


//function to hide options menu
function hideOptionsMenu() {
    var menu = $(".optionsMenu");
    if(menu.css("display") != "none") {
        menu.css("display", "none");
    }
}

//function for show optionsmenu at songs

function showOptionsMenu(button) {
    var songId = $(button).prevAll(".songId").val();
    var menu = $(".optionsMenu");
    var menuWidth = menu.width();
    menu.find(".songId").val(songId);

    var scrollTop = $(window).scrollTop(); //Distance from top of window to top of the document
    var elementOffset = $(button).offset().top; //Distance from top of document

    var top = elementOffset - scrollTop;
    var left = $(button).position().left;

    menu.css({"top": top + "px", "left": left - menuWidth + "px", "display": "inline"});
}

//function for formatTime for the song

function formatTime(seconds) {
    var time = Math.round(seconds);
    var minutes = Math.floor(time/60); // Rounds down the number
    var seconds = time - (minutes * 60);

    var extraZero;

    if(seconds < 10) {
        extraZero = "0";
    }
    else {
        extraZero = "";
    }

    return minutes + ":" + extraZero + seconds;
}

//function for update time progressBar 

function updateTimeProgressBar(audio) {
    $(".progressTime.current").text(formatTime(audio.currentTime));
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

    var progress = audio.currentTime / audio.duration * 100;
    $(".playBackBar .progress").css("width", progress + "%");
}

//Function for volume prograssBar

function updateVolumeProgressBar(audio) {
    var volume = audio.volume * 100;
    $(".volumeBar .progress").css("width", volume + "%");
}

//Function for Audio regarding play, timeupdate, volume change etc

function Audio() {


    this.currentlyPlaying;
    this.audio = document.createElement('audio');

    this.audio.addEventListener("ended", function() {
        nextSong();
    });

    this.audio.addEventListener("canplay", function() {
        // This refers to the object that the event was called on
        var duration = formatTime(this.duration);
        $(".progressTime.remaining").text(duration);
        
    });

    // EventListener to updateTime in the progressbar

    this.audio.addEventListener("timeupdate", function() {
        if(this.duration) {
            updateTimeProgressBar(this);
        }
    });

    // EventListener to Volumechange progressbar

    this.audio.addEventListener("volumechange", function(){
        updateVolumeProgressBar(this);
    }); 

    this.setTrack = function(track) {
        this.currentlyPlaying = track;
        this.audio.src = track.path;
    }

    this.play = function() {
        this.audio.play();
    }

    this.pause = function() {
        this.audio.pause();
    }

    this.setTime = function(seconds) {
        this.audio.currentTime = seconds;
    }
}