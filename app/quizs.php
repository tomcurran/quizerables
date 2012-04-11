<?php

require_once('init.php');

if (!$lq->user) {
	header('Location: index.php');
}

if(isset($_REQUEST['request'])) {
	switch ($_REQUEST['request']) {
			case 'createQuiz':
				$quiz = new Quiz();
				$quiz->title = $_REQUEST['quizTitle'];
				$quiz->user_id = $lq->user->id;
				$quiz->save();
				echo $quiz->encodeJSON();
				break;
			case 'deleteQuiz':
				$quiz = Quiz::get($_REQUEST['quizId']);
				$quiz->delete();
				break;
			case 'loadQuizs':
				$quizs = Quiz::getAllByUser($lq->user);
				$qs = array();
				if($quizs) {
					foreach ($quizs as $quiz) {
						$qs[] = $quiz->getJSONData();
					}
				}
					
				echo json_encode($qs);
				break;
	}
} else {
	$args['user'] = $lq->user;
	$args['scripts'][] = 'quizs.js';
	$lq->render('quizs.html', $args);
}

?>