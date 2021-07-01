(() => {
  var xhr = new XMLHttpRequest();
  console.log(window.location.search);
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get("id");
  xhr.open(
    "GET",
    `http://localhost/OnlineQuiz/api/quiz/getQuizById.php?id=${id}`,
    true
  );

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let quizInfo = JSON.parse(xhr.responseText);
      let quizData = quizInfo.data;

      let questionAnswerMap = new Map(); // question id - answers array map
      let questionAudioMap = new Map(); // question id - is audio map
      let questionTextMap = new Map(); // question id - is text map
      let questionIdMap = new Map(); // question id - question title map

      for (let i = 0; i < quizData.length; i++) {
        const questionTitle = quizData[i].title;
        const questionAnswer = quizData[i].content;
        const questionId = quizData[i].question_id;

        if (questionAnswerMap.has(questionId)) {
          const values = questionAnswerMap.get(questionId);
          values.push(questionAnswer);
          questionAnswerMap.set(questionId, values);
        } else {
          questionAnswerMap.set(questionId, [questionAnswer]);
          questionAudioMap.set(questionId, quizData[i].is_audio);
          questionTextMap.set(questionId, quizData[i].is_text);
          questionIdMap.set(questionId, questionTitle);
        }
      }

      let questionsSection = document.getElementById("questions");
      let ol = document.createElement("ol");
      questionsSection.appendChild(ol);

      for (const [questionId, answers] of questionAnswerMap) {
        let div = document.createElement("div");
        div.className = "list-item";

        let src = "../wav-files/" + questionId + ".wav";
        let au = document.createElement("audio");
        au.controls = true;
        au.src = src;

        let li = document.createElement("li");
        if (questionAudioMap.get(questionId) === "1") {
          li.innerHTML = "Listen the given question:";
          div.appendChild(li);
          div.appendChild(au);
        } else {
          div.appendChild(li);
          li.innerHTML = questionIdMap.get(questionId);
        }

        if (questionTextMap.get(questionId) === "0") {
          let customSelect = document.createElement("div");
          customSelect.className = "select";

          let sel = document.createElement("select");
          let defaultOption = document.createElement("option");
          defaultOption.value = 0;
          defaultOption.text = "Select answer";
          defaultOption.disabled = true;
          defaultOption.selected = true;
          sel.add(defaultOption);

          for (let i = 0; i < answers.length; i++) {
            let opt = document.createElement("option");
            opt.value = i + 1;
            opt.text = answers[i];
            sel.add(opt);
          }
          customSelect.appendChild(sel);
          div.appendChild(customSelect);
        } else {
          let inputText = document.createElement("textarea");
          inputText.type = "text";
          div.appendChild(inputText);
        }

        ol.appendChild(div);
      }
    }
  };
  xhr.send();
})(this);
