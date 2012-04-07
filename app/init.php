<?php

define('ROOT', dirname(__FILE__) . '/');
require_once('classes/Loader.php');
Loader::register();
$quiz = new Quiz();

?>