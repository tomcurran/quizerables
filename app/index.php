<?php

require_once('init.php');

if (!$lq->user) {
	if(isset($_POST['login'])) {
		$username = isset($_POST['username']) ? $_POST['username'] : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		$lq->user = User::login($username, $password);
	}

	if(isset($_POST['signup'])) {
		$username = isset($_POST['username']) ? $_POST['username'] : '';
		$email = isset($_POST['username']) ? $_POST['email'] : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		
		if (User::signup($username, $email, $password)) {
			$lq->user = User::login($username, $password);
		}
	}
}

if ($lq->user) {
	$args['quizzes'] = $lq->user->getQuizs();
	$args['loggedin'] = true;
} else {
	$args['loggedin'] = false;
}

$lq->render('index.html', $args);

?>