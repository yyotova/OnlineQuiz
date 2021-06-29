const addQuiz = function(event) {
    console.log('here');
    event.preventDefault();
}

const form = document.getElementById('new-quiz');

form.addEventListener('submit', addQuiz, true);