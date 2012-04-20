$(loadQuiz);

function loadQuiz() {
	$.post('editQuiz.php', {
		request: 'loadQuiz',
		id: $('#id').val(),
		csrf: $('#csrf').val()
	}, function(json) {
		$('#id').val(json.id);
		$('#title').val(json.title);
		$('#description').val(json.description);
		if (json.questions) {
			$('#questions').html('');
			$.each(json.questions, function(i, question) {
				addQuestion(question);
			});
		} else {
			$('#questions').html('WHY NO QUESTIONS?');
		}
		$('#quizDetails').focusout(saveQuizDetails);
		$('#newQuestion').click(newQuestion);
		$('#viewQuiz').click(function() {
			window.location = "./doQuiz.php?id=" + $('#id').val();
		});
	}, 'json');
}

function saveQuizDetails() {
	$.post('editQuiz.php', {
		request: 'saveQuiz',
		id: $('#id').val(),
		title: $('#title').val(),
		description: $('#description').val(),
		csrf: $('#csrf').val()
	}, 'json');
}

function newQuestion() {
	$('#newQuestion').attr('disabled', true);
	$.post('editQuiz.php', {
		request: 'createQuestion',
		id: $('#id').val(),
		csrf: $('#csrf').val()
	}, addQuestion, 'json')
	.complete(function(){    
	    $('#newQuestion').attr('disabled', false);
	});
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
	$('<img/>', {
    	src: 'images/delete.png',
		title: 'Delete Question',
		alt: 'Delete Question'
    }).appendTo(
	$('<a/>', {
		href: 'javascript:void(0);'
	}).click(deleteQuestion).appendTo(div));
	$('<br />').appendTo(div);
	div.append('Required');
	$('<input />', {
		type: 'checkbox',
		name: 'required'
	}).prop('checked', question.required == '1').click(saveQuestion).appendTo(div);
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
		questionRequired: required,
		csrf: $('#csrf').val()
	}, function(){}, 'json');
}

function deleteQuestion() {
	var id = $(this).siblings('input[name="id"]').val();
	var that = this;
	$.post('editQuiz.php', {
		request: 'deleteQuestion',
		id: $('#id').val(),
		questionId: id,
		csrf: $('#csrf').val()
	}, function() {
		$(that).parent().slideUp();
		$(that).parent().remove();
	}, 'json');
}
