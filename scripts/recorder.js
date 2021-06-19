//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; //stream from getUserMedia()
var rec; //Recorder.js object
var input; //MediaStreamAudioSourceNode we'll be recording

var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext //audio context to help us record

var recordName;
var recordBlob;

var quizInput = document.getElementById("quiz");
var questionInput = document.getElementById("question");

var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");
var submitButton = document.getElementById("submitButton");

//add events to those 2 buttons
recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
submitButton.addEventListener("click", submitRecord);

quizInput.addEventListener("input", inputData);
questionInput.addEventListener("input", inputData);

function inputData() {
    if (quizInput.value && questionInput.value) {
        recordButton.disabled = false;
    }
}

function submitRecord(event) {
    event.preventDefault();

    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    fd.append("audio_data", recordBlob, recordName);
    xhr.open("POST", "./upload.php", true);
    xhr.send(fd);
}

function startRecording() {
    var constraints = { audio: true, video: false }

    //disable the record button until we get a success or fail from getUserMedia()

    recordButton.disabled = true;
    stopButton.disabled = false;

    navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
        //getUserMedia() success, stream created, initializing Recorder.js

        /*
        	create an audio context after getUserMedia is called
        	sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
        	the sampleRate defaults to the one set in your OS for your playback device
        */
        audioContext = new AudioContext();

        //assign to gumStream for later use
        gumStream = stream;

        //use the stream
        input = audioContext.createMediaStreamSource(stream);

        rec = new Recorder(input, { numChannels: 1 })

        //start the recording process
        rec.record()
    }).catch(function(err) {
        //enable the record button if getUserMedia() fails
        recordButton.disabled = false;
        stopButton.disabled = true;
    });
}

function stopRecording() {
    //disable the stop button, enable the record too allow for new recordings
    stopButton.disabled = true;
    recordButton.disabled = true;

    if (quizInput.value && questionInput.value) {
        submitButton.disabled = false;
    }

    //tell the recorder to stop the recording
    rec.stop();

    //stop microphone access
    gumStream.getAudioTracks()[0].stop();

    //create the wav blob and pass it on to createDownloadLink
    rec.exportWAV(createDownloadLink);
}

function createDownloadLink(blob) {
    recordBlob = blob;

    var url = URL.createObjectURL(blob);
    var au = document.createElement('audio');
    var li = document.createElement('li');
    var link = document.createElement('a');
    //name of .wav file to use during upload and download (without extendion)
    recordName = quizInput.value + '_' + questionInput.value;

    //add controls to the <audio> element
    au.controls = true;
    au.src = url;

    //save to disk link
    link.href = url;
    link.download = recordName + ".wav"; //download forces the browser to donwload the file using the  filename
    link.innerHTML = "Save to disk";

    //add the new audio element to li
    li.appendChild(au);

    //add the filename to the li
    li.appendChild(document.createTextNode(recordName + ".wav "))

    //add the save to disk link to li
    li.appendChild(link);

    //add the li element to the ol
    recordingsList.appendChild(li);
}