$(function() {	$('#createTitle').Watermark('Enter quiz name');	loadQuizs();});function loadQuizs() {	$.post('createQuiz.php', {		request: 'loadQuizs',		csrf: $('#csrf').val()	}, function(quizs) {		$('#loadingMessage').slideUp().remove();		if (quizs.length == 0) {			noQuizs();		}		$.each(quizs, function(i, quiz) {			addQuiz(quiz);		});		$('#create').click(newQuiz);	}, 'json');}function addQuiz(quiz) {	$('.noQuizMessage').remove();	var div = $('<div class="quiz">').appendTo('#quizs');	$('<span class="quizTitle">').html(quiz.title).appendTo(div);	$('<input/>', {		type: 'hidden',		value: quiz.id,		class: 'id'	}).appendTo(div);	$('<input/>', {		type: 'button',		value: 'Statistics',		class: 'statistics'	}).appendTo(div);	$('<input/>', {		type: 'button',		value: 'Delete',		class: 'delete'	}).click(deleteQuiz).appendTo(div);	$('<input/>', {		type: 'button',		value: 'Edit',		class: 'edit'	}).click(editQuiz).appendTo(div);}function newQuiz() {	$('#create').attr('disabled', true);	$.post('createQuiz.php', {		request: 'createQuiz',		quizTitle: $('#createTitle').val(),		csrf: $('#csrf').val()	}, function(data) {		$('#createTitle').val('');		addQuiz(data);	}, 'json')	.complete(function(){    	    $('#create').attr('disabled', false);	});}function editQuiz() {	document.location.href = 'editQuiz.php?id=' + $(this).siblings('.id').val();}function deleteQuiz() {	var id = $(this).siblings('.id').val();	$.post('createQuiz.php', {		request: 'deleteQuiz',		quizId: id,		csrf: $('#csrf').val()	}, function() {		$('.id[value=' + id + ']').parent().slideUp().remove();		if($('#quizs').length == 0) {			noQuizs();		}	}, 'json');}function noQuizs() {	var div = $('<div class="noQuizMessage">').appendTo('#quizs');	$('<strong>').html('Y u no create quizzess?!').appendTo(div);	$('<br />').appendTo(div);	$('<img src="images/pearcat.jpg" />').appendTo(div);}