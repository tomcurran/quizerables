<?php

class Loader {

	static $load_paths = array(
		'lib/model/',
		'lib/controller/',
		'lib/view/Twig/'
	);

	static function register() {
		spl_autoload_register('Loader::load');
	}
	
	static function load($name) {
		$name = preg_replace('/Twig_/', '', $name, 1);
		if (class_exists($name, false)) return;

		foreach (self::$load_paths as $path) {
			$filename = ROOT . $path . $name . '.php';
			if (file_exists($filename)){
				require_once($filename);
				return;
			}
		}
	}

}
?>