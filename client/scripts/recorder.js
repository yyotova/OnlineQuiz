//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

let gumStream; //stream from getUserMedia()
let rec; //Recorder.js object
let input; //MediaStreamAudioSourceNode we'll be recording

let AudioContext = window.AudioContext || window.webkitAudioContext;
let audioContext //audio context to help us record

let recordName;
let recordBlob;

let recordButton = document.getElementById("recordButton");
let stopButton = document.getElementById("stopButton");

//add events to those 2 buttons
recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);

function startRecording() {
    let constraints = { audio: true, video: false }
    recordButton.disabled = true;
    stopButton.disabled = false;
    //disable the record button until we get a success or fail from getUserMedia()

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
    stopButton.disabled = true;
    recordButton.disabled = false;
    //tell the recorder to stop the recording
    rec.stop();

    //stop microphone access
    gumStream.getAudioTracks()[0].stop();

    //create the wav blob and pass it on to createDownloadLink
    rec.exportWAV(createDownloadLink);
}

function createDownloadLink(blob) {
    recordBlob = blob;

    let url = URL.createObjectURL(blob);
    let au = document.createElement('audio');

    //add controls to the <audio> element
    au.controls = true;
    au.src = url;

    //add the li element to the ol
    recordingsList.appendChild(au);
}