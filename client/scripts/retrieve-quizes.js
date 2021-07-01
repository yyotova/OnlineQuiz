(() => {
  var questionRequest = new XMLHttpRequest();
  questionRequest.open(
    "GET",
    "http://localhost:8080/OnlineQuiz/api/question/getQuestions.php",
    true
  );

  let questions;
  let questionMap = new Map(); // quiz ID - questions array map

  questionRequest.onreadystatechange = function () {
    if (questionRequest.readyState == 4 && questionRequest.status == 200) {
      questions = JSON.parse(questionRequest.responseText);

      for (let i = 0; i < questions.data.length; i++) {
        const quizId = questions.data[i].quiz_id;
        const question = questions.data[i].title;

        if (questionMap.has(quizId)) {
          const values = questionMap.get(quizId);
          values.push(question);
          questionMap.set(quizId, values);
        } else {
          questionMap.set(quizId, [question]);
        }
      }
    }
  };
  questionRequest.send();

  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "http://localhost:8080/OnlineQuiz/api/quiz/getUserQuizes.php?id=60bca4a76d933",
    true
  );

  let selectedQuizId;
  let selectedQuestionId;

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var quizes = JSON.parse(xhr.responseText);
      let sel = document.createElement("select");
      sel.className = "select";
      sel.setAttribute("id", "choose-quiz");

      let defaultQuizOption = document.createElement("option");
      defaultQuizOption.value = 0;
      defaultQuizOption.disabled = true;
      defaultQuizOption.hidden = true;
      defaultQuizOption.selected = true;
      defaultQuizOption.text = "Select quiz";
      sel.add(defaultQuizOption);

      selectedQuizId = quizes.data[0].quiz_id;
      for (let i = 0; i < quizes.data.length; i++) {
        let opt = document.createElement("option");
        opt.value = i + 1;
        opt.text = quizes.data[i].title;
        sel.add(opt);
      }

      let sectionQuiz = document.getElementById("quiz");
      sectionQuiz.appendChild(sel);

      let selQuestion = document.createElement("select");
      selQuestion.className = "select";
      selQuestion.setAttribute("id", "choose-question");

      let defaultQuestionOption = document.createElement("option");
      defaultQuestionOption.value = 0;
      defaultQuestionOption.disabled = true;
      defaultQuestionOption.hidden = true;
      defaultQuestionOption.selected = true;
      defaultQuestionOption.text = "Select question";
      selQuestion.add(defaultQuestionOption);

      let sectionQuestion = document.getElementById("question");
      sectionQuestion.appendChild(selQuestion);

      sel.addEventListener("change", (event) => {
        selectedQuizId = quizes.data[event.target.value - 1].quiz_id;

        while (selQuestion.options.length > 0) {
          selQuestion.remove(0);
        }

        selQuestion.add(defaultQuestionOption);

        let questions = questionMap.get(selectedQuizId);
        for (let i = 0; i < questions.length; i++) {
          let opt = document.createElement("option");
          opt.value = i + 1;
          opt.text = questions[i];
          selQuestion.add(opt);
        }
      });
    }
  };
  xhr.send();

  const myForm = document.getElementById("myForm");
  const inpFile = document.getElementById("inpFile");

  myForm.addEventListener("submit", () => {
    const selection = document.getElementById("choose-question");
    const questionName = questionMap.get(selectedQuizId)[selection.value - 1];
    
    for (let i = 0; i < questions.data.length; i++) {
      if (questions.data[i].title === questionName) {
        selectedQuestionId = questions.data[i].id;
      }
    }

    const endpoint = "http://localhost:8080/OnlineQuiz/api/upload/upload.php";
    const formData = new FormData();

    formData.append("inpFile", inpFile.files[0]);
    formData.append("fileName", selectedQuestionId);

    fetch(endpoint, {
      method: "post",
      body: formData,
    }).catch(console.error);
  });
})(this);
