<?php

require_once('init.php');

if (!$lq->user) {
	header('Location: index.php');
}

if(isset($_REQUEST['request'])) {
	switch ($_REQUEST['request']) {
			case 'createQuiz':
				$quiz = new Quiz();
				$quiz->title = $_REQUEST['title'];
				$quiz->user_id = $lq->user->id;
				$quiz->save();
				echo $quiz->encodeJSON();
				break;
			case 'deleteQuiz':
				$quiz = Quiz::get($_REQUEST['id']);
				$quiz->delete();
				break;
			case 'loadQuizs':
				$quizs = Quiz::getAllByUser($lq->user);
				foreach ($quizs as $quiz) {
					$qs[] = $quiz->encodeJSON();
				}
				echo json_encode($quizs);
				break;
	}
} else {
	$args['user'] = $lq->user;
	$args['quizs'] = $lq->user->getQuizs();
	$lq->render('quizs.html', $args);
}

?>