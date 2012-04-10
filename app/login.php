<?php

require_once('init.php');

if(!empty($_POST['username']) && !empty($_POST['password'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		if ($lq->user = User::login($username, $password)) {
			/* WINNING */
			echo '<img src="images/wincat.jpg" />';
		} else {
			/* Login fail */
			echo '<img src="images/problemcat.jpg" />';
	}
} else {
	/* Fields not submit */
	echo '<img src="images/problemcat.jpg" />';
}

echo '<a href="index.php">Click here</a>';

?>