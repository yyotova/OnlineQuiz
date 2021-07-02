(() => {
  var xhr = new XMLHttpRequest();
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get("id");
  xhr.open(
    "GET",
    `http://localhost/OnlineQuiz/api/quiz/getQuizById.php?id=${id}`,
    true
  );

  let questionAnswerMap = new Map(); // question id - answers array map
  let questionAnswerIdMap = new Map(); // question id - answer ids array map
  let questionAudioMap = new Map(); // question id - is audio map
  let questionTextMap = new Map(); // question id - is text map
  let questionIdMap = new Map(); // question id - question title map

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let quizInfo = JSON.parse(xhr.responseText);
      let quizData = quizInfo.data;

      for (let i = 0; i < quizData?.length; i++) {
        const questionTitle = quizData[i].title;
        const questionAnswer = quizData[i].content;
        const questionAnswerId = quizData[i].answer_id;
        const questionId = quizData[i].question_id;

        if (questionAnswerMap.has(questionId)) {
          const values = questionAnswerMap.get(questionId);
          values.push(questionAnswer);
          questionAnswerMap.set(questionId, values);

          const valuesId = questionAnswerIdMap.get(questionId);
          valuesId.push(questionAnswerId);
          questionAnswerIdMap.set(questionId, valuesId);
        } else {
          questionAnswerMap.set(questionId, [questionAnswer]);
          questionAnswerIdMap.set(questionId, [questionAnswerId]);
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
          sel.setAttribute("id", questionId);

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
          inputText.setAttribute("id", questionId);
          div.appendChild(inputText);
        }

        ol.appendChild(div);
      }
    }
  };
  xhr.send();

  const myForm = document.getElementById("quiz-form");
  const endpoint = "http://localhost/OnlineQuiz/api/answer/setAnswer.php";
  const userId = '60bca4a76d933';

  myForm.addEventListener("submit", () => {
    const questionIds = [...questionIdMap.keys()];

    for (let i = 0; i < questionIds.length; i++) {
      const answer = document.getElementById(questionIds[i]);

      if (questionTextMap.get(questionIds[i]) === "0") {
        const answerOptionId = questionAnswerIdMap.get(questionIds[i])[
          answer.value - 1
        ];

        const formData = new FormData();

        formData.append("answerId", answerOptionId);
        formData.append("userId", userId);
        formData.append("answerContent", '');

        fetch(endpoint, {
          method: "post",
          body: formData,
        }).catch(console.error);
      } else {
        const answerId = questionAnswerIdMap.get(questionIds[i])[0];

        const text = answer.value;

        const formData = new FormData();

        formData.append("answerId", answerId);
        formData.append("userId", userId);
        formData.append("answerContent", text);

        fetch(endpoint, {
          method: "post",
          body: formData,
        }).catch(console.error);
      }
    }
  });
})(this);
