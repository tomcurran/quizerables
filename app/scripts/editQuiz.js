$(loadQuiz);

function loadQuiz() {
	$.getJSON('editQuiz.php', {
		request: 'loadQuiz',
		id: $('#id').val()
	}, function(json) {
		$('#id').val(json.id);
		$('#title').val(json.title);
		$('#description').val(json.description);
		$('#questions').html('');
		$.each(json.questions, function(i, question) {
				addQuestion(question);
			});
		$('#quizDetails').focusout(saveQuizDetails);
		$('#newQuestion').click(newQuestion);
	});
}

function saveQuizDetails() {
	$.post('editQuiz.php', {
		request: 'saveQuiz',
		id: $('#id').val(),
		title: $('#title').val(),
		description: $('#description').val(),
	});
}

function newQuestion() {
	$.getJSON('editQuiz.php', {
		request: 'createQuestion',
		id: $('#id').val(),
	}, addQuestion);
}

function addQuestion(question) {
	var div = $('<div class="question">');
	$('<input />', {
		type: 'hidden',
		name: 'id',
		value: question.id
	}).appendTo(div);
	$('<input />', {
		type: 'text',
		name: 'text',
		value: question.text
	}).focusout(saveQuestion).appendTo(div);
	div.append('Required: ');
	$('<input />', {
		type: 'checkbox',
		name: 'required'
	}).prop('checked', question.required == '1').click(saveQuestion).appendTo(div);
	$('<input />', {
		type: 'button',
		name: 'delete',
		value: 'Delete Question',
	}).click(deleteQuestion).appendTo(div);
	div.appendTo('#questions');
}

function saveQuestion() {
	var children = $(this).siblings().andSelf();
	var id = children.filter('input[name="id"]').val();
	var text = children.filter('input[name="text"]').val();
	var required = children.filter('input:checked').length;
	$.post('editQuiz.php', {
		request: 'saveQuestion',
		id: $('#id').val(),
		questionId: id,
		questionText: text,
		questionRequired: required
	});
}

function deleteQuestion() {
	var id = $(this).siblings('input[name="id"]').val();
	var that = this;
	$.post('editQuiz.php', {
		request: 'deleteQuestion',
		id: $('#id').val(),
		questionId: id
	}, function() {
		$(that).parent().slideUp();
		$(that).parent().remove();
	});
}