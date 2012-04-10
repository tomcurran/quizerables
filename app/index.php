<?php

require_once('init.php');

if ($lq->user) {
	$args['user'] = $lq->user;
	$args['quizs'] = $lq->user->getQuizs();
	$lq->render('quizs.html', $args);

} else {
	$args['user'] = false;
	$lq->render('index.html', $args);
}

?>