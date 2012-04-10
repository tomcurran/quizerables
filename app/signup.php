 <?php
 
 require_once('init.php');
 
 if(!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	if (User::signup($username, $email, $password)) {
		$lq->user = User::login($username, $password);
		/* WINNING */
		echo '<img src="images/wincat.jpg" />';
	} else {
		/* Error handling for same user name */
		echo '<img src="images/problemcat.jpg" />';
	}
} else {
	/* Error handling for fields not posted */
	echo '<img src="images/problemcat.jpg" />';
}

echo '<a href="index.php">Click here</a>';

?>