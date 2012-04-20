$(loadQuiz);

function loadQuiz() {
	$.post('viewAnswers.php', {
		request: 'loadQuiz',
		id: $('#id').val(),
		csrf: $('#csrf').val()
	}, function(json) {
		$('#id').val(json.id);
		$('#title').val(json.title);
		if (json.questions) {
			$('#questions').html('');
			$.each(json.questions, function(i, question) {
				addQuestion(question);
			});
		} else {
			$('#questions').html('WHY NO QUESTIONS?');
		}
	}, 'json');
}

function addQuestion(question) {
	var div = $('<div class="question">');
	div.appendTo($('#questions'));
	$('<h5>').html(question.text).appendTo(div);
	if (question.answers) {
		$.each(question.answers, function(i, answer) {
			addAnswer(div, answer);
		});
	} else {
		div.html('No answers');
	}
}

function addAnswer(question, answer) {
	var div = $('<div class="answer">');
	div.appendTo(question);
	div.html(answer.text);
}
