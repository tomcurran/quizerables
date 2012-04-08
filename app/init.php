<?php

define('ROOT', dirname(__FILE__) . '/');
require_once('classes/Loader.php');
Loader::register();
session_start();
$lq = new LQ();
$lq->user = User::fromSession();

?>