(() => {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "http://localhost/OnlineQuiz/api/quiz/getQuizById.php?id=67df61fc-c6ba-11eb-b8bc-0242ac130003", true);

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      console.log('here');
      var quiz = JSON.parse(xhr.responseText);
      console.log(quiz.data);
      
      mainContent = document.getElementById("main-content");
      mainContent.innerHTML += `<h4>${quiz.data.title}</h4>`;
    }
  }
  xhr.send();

})(this); 