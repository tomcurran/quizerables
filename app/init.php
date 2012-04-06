<?php

define('ROOT', dirname(__FILE__) . '/');

$loader_paths = array(
    '',
    'lib/Twig/'
);

function loader($name) {
	global $loader_paths;

    $name = preg_replace('/Twig_/', '', $name, 1);
    if (class_exists($name, false)) return;

    foreach ($loader_paths as $path) {
        $filename = ROOT . $path . $name . '.php';
        if (file_exists($filename)){
            require_once($filename);
            return;
        }
    }

}

spl_autoload_register('loader');

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
  'cache' => 'templates/cache',
));
$twig->setCache(false);

$db = new DB();

?>