(() => {
  var xhr = new XMLHttpRequest();
  console.log(window.location.search);
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get('id');
  xhr.open("GET", `http://localhost/OnlineQuiz/api/quiz/getQuizById.php?id=${id}`, true);

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var quizInfo = JSON.parse(xhr.responseText);
      var quizData = quizInfo.data;

      let questionAnswerMap = new Map();
      for (let i = 0; i < quizData?.length; i++) {
        const questionTitle = quizData[i].title;
        const questionAnswer = quizData[i].content;

        if (questionAnswerMap.has(questionTitle)) {
          const values = questionAnswerMap.get(questionTitle);
          values.push(questionAnswer);
          questionAnswerMap.set(questionTitle, values);
        } else {
          questionAnswerMap.set(questionTitle, [questionAnswer]);
        }
      }

      let questionsSection = document.getElementById("questions");
      let ol = document.createElement("ol");
      questionsSection.appendChild(ol);

      for (const [questionTitle, answers] of questionAnswerMap) {
        let div = document.createElement("div");

        let li = document.createElement('li');
        li.innerHTML = questionTitle;
        div.appendChild(li);

        let sel = document.createElement("select");
        let defaultOption = document.createElement("option");
        defaultOption.value = 0;
        defaultOption.text = 'Select answer';
        defaultOption.disabled = true;
        defaultOption.selected = true;
        sel.add(defaultOption);

        for (let i = 0; i < answers.length; i++) {
          let opt = document.createElement("option");
          opt.value = i + 1;
          opt.text = answers[i];
          sel.add(opt);
        }
        div.appendChild(sel);

        ol.appendChild(div);
      }

    }
  }
  xhr.send();

})(this);