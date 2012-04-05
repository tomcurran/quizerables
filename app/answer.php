<?php

require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
  'cache' => 'templates/compilation_cache',
));
$twig->setCache(false);

require_once("DB.php");
$db = new DB();

foreach (array_keys($_POST) as $key) {
	if (is_int($key) && isset($_POST[$key])) {
		if (!$db->answerQuestion($key, $_POST[$key])) { echo 'fail';}
	}
}

echo $twig->render("answer.html");

?>