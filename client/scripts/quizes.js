(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "http://localhost/OnlineQuiz/api/quiz/getUserQuizes.php?id=60bca4a76d933", true);

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let quizes = JSON.parse(xhr.responseText);

      quizes.data.map(quiz => {
        mainContent = document.getElementById("main-content");
        let div = document.createElement("div");
        div.className = "quiz-container";
        
        let a = document.createElement("a");
        a.href = `quiz.html?id=${quiz.quiz_id}`;
        a.innerText = quiz.title;

        div.appendChild(a);
        mainContent.appendChild(div);
      })
    }
  }
  xhr.send();

})(this);
