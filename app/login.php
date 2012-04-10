<?php

require 'init.php';

if (isset($_POST['login'])) {
	if(isset($_POST['login'])) {
		$username = isset($_POST['username']) ? $_POST['username'] : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		$lq->user = User::login($username, $password);
	}
}

if ($lq->user) {
	echo '<img src="images/wincat.jpg" />';
}

else {
	echo '<img src="images/problemcat.jpg" />';
}

echo '<a href="index.php">Click here</a>';