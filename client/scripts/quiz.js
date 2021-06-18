(() => {
  var xhr = new XMLHttpRequest();
  console.log(window.location.search);
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get('id');
  xhr.open("GET", `http://localhost/OnlineQuiz/api/quiz/getQuizById.php?id=${id}`, true);

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var quiz = JSON.parse(xhr.responseText);
      
      mainContent = document.getElementById("quiz");
      mainContent.innerHTML += `<h4>${quiz.data.title}</h4>`;
    }
  }
  xhr.send();

})(this); 