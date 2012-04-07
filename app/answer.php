<?php

require_once 'init.php';

foreach (array_keys($_POST) as $key) {
	if (is_int($key) && isset($_POST[$key])) {
		if (!$db->answerQuestion($key, $_POST[$key])) { echo 'fail';}
	}
}

$quiz->render('answer.html');

?>