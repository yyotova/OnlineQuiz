var submitButton = document.getElementById("submitButton");
submitButton.addEventListener("click", submitRecord);

var recordFile = document.getElementById("recordId");

function submitRecord() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost:8080/OnlineQuiz/api/record/record.php", true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('here');
            var quiz = JSON.parse(xhr.responseText);
            console.log(quiz.recordFile);
        }
    }

    var file = new FormData();
    file.append("recordFile", recordFile);
    xhr.send(file);
}