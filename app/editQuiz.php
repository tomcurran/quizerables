<?php

require_once('init.php');

if(!$lq->user) {
	header('Location: index.php');
}

if(isset($_REQUEST['request'])) {
	$quiz = Quiz::get($_REQUEST['id']);
	if ($quiz->user_id != $lq->user->id) {
		die('GET THE FUCK OOT!');
	}
	switch ($_REQUEST['request']) {
		case 'loadQuiz':
			echo $quiz->encodeJSON();
			break;
		case 'saveQuiz':
			print_r($_REQUEST);
			$quiz->title = $_REQUEST['title'];
			$quiz->description = $_REQUEST['description'];
			$quiz->save();
			break;
		case 'saveQuestion':
			$question = Question::get($_REQUEST['questionId']);
			$question->text = $_REQUEST['questionText'];
			$question->required = $_REQUEST['questionRequired'];
			$question->save();
			break;
		case 'createQuestion':
			$question = new Question();
			$question->quiz_id = $quiz->id;
			$question->save();
			echo $question->encodeJSON();
			break;
		case 'deleteQuestion':
			$question = Question::get($_REQUEST['questionId']);
			$question->delete();
			break;
	}
} else {
	if(isset($_REQUEST['id'])) {
		$quiz = Quiz::get($_REQUEST['id']);
		if ($quiz->user_id != $lq->user->id) {
			die('GET THE FUCK OOT!');
		}
	} else {
		$quiz = new Quiz();
		$quiz->user_id = $lq->user->id;
		$quiz->save();
	}
	$args['user'] = $lq->user;
	$args['scripts'][] = 'editQuiz.js';
	$args['quiz'] = $quiz;
	$lq->render('editQuiz.html', $args);
}
?>