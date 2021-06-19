(() => {
    var xhr = new XMLHttpRequest();
    console.log(window.location.search);
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    xhr.open("GET", `http://localhost:8080/OnlineQuiz/api/quiz/getQuizById.php?id=${id}`, true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var quizInfo = JSON.parse(xhr.responseText);
            var quizData = quizInfo.data;

            console.log(quizData);

            let questionAnswerMap = new Map(); // question title - answers array map
            let questionAudioMap = new Map(); // question title - is audio map
            let questionTextMap = new Map(); // question title - is text map

            for (let i = 0; i < quizData.length; i++) {
                const questionTitle = quizData[i].title;
                const questionAnswer = quizData[i].content;

                if (questionAnswerMap.has(questionTitle)) {
                    const values = questionAnswerMap.get(questionTitle);
                    values.push(questionAnswer);
                    questionAnswerMap.set(questionTitle, values);
                } else {
                    questionAnswerMap.set(questionTitle, [questionAnswer]);
                    questionAudioMap.set(questionTitle, quizData[i].is_audio);
                    questionTextMap.set(questionTitle, quizData[i].is_text);
                }
            }

            console.log(questionAudioMap);

            let questionsSection = document.getElementById("questions");
            let ol = document.createElement("ol");
            questionsSection.appendChild(ol);

            for (const [questionTitle, answers] of questionAnswerMap) {
                let div = document.createElement("div");

                let src = "../wav-files/PHP_question1.wav";
                let au = document.createElement('audio');
                au.controls = true;
                au.src = src;

                let li = document.createElement('li');
                if (questionAudioMap.get(questionTitle) === "1") {
                    li.appendChild(au);
                } else {
                    li.innerHTML = questionTitle;
                }
                div.appendChild(li);

                if (questionTextMap.get(questionTitle) === "0") {
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
                } else {
                    let inputText = document.createElement("input");
                    inputText.type = "text";
                    div.appendChild(inputText);
                }

                ol.appendChild(div);
            }

        }
    }
    xhr.send();

})(this);