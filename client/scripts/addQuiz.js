const addQuiz = function() {
  var xhr = new XMLHttpRequest();
  xhr.open(
    "POST",
    `http://localhost/OnlineQuiz/api/quiz/createQuiz.php`,
    true
  );
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  const title = document.getElementById('title').value;
  const description = document.getElementById('description').value;
  const maxScore = document.getElementById('maxScore').value;
  const levelId = document.getElementById('levelId').value;

  const params = {
    'title': title,
    'description': description,
    'maxScore': maxScore,
    'levelId': levelId
  };

  xhr.send(JSON.stringify(params));
}

const form = document.getElementById('new-quiz');

form.addEventListener('submit', addQuiz, true);