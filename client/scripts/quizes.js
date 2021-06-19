(() => {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "http://localhost/OnlineQuiz/api/quiz/getUserQuizes.php?id=60bca4a76d933", true);

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var quizes = JSON.parse(xhr.responseText);

      quizes.data.map(quiz => {
        console.log(quiz);
        console.log(quiz.quiz_id);

        let info = '';
        info += `
          <div>
          <a href="quiz.html?id=${quiz.quiz_id}">${quiz.title}</a>
          </div>
        `;
        mainContent = document.getElementById("main-content");
        mainContent.innerHTML += info;

      })

    }
  }
  xhr.send();

})(this);
