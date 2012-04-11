<?php

require_once('init.php');

if ($lq->user) {
	header('Location: quizs.php');

} else {
	$args['user'] = false;
	$lq->render('index.html', $args);
}

?>