

var currentPlayList = [];
var shufflePlayList = [];
var tempPlayList = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;

function openPage(url) {

	if(url.indexOf("?") == -1) {
		url = url + "?";
	}

	var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
	console.log(encodedUrl);
    $("#mainContent").load(encodedUrl);
	$("body").scrollTop(0);
    history.pushState(null,null,url);

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