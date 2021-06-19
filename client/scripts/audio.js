(() => {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "http://localhost/OnlineQuiz/api/quiz/getUserQuizes.php?id=60bca4a76d933", true);
  
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var quizes = JSON.parse(xhr.responseText);
        let sel = document.createElement('select');
        sel.setAttribute("id", "choose-quiz");

        for (let i=0; i< quizes.data.length; i++) {
          let opt = document.createElement("option");
          opt.value = i + 1;
          opt.text = quizes.data[i].title;
          sel.add(opt);
        }

        let sectionQuiz = document.getElementById('quiz');
        sectionQuiz.appendChild(sel);
      }
    }
    xhr.send();
  
  })(this);
  