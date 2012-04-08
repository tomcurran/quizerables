<?php

class LQ {
	
	public $pdo;
	public $twig;
	public $user;
	
	public function __construct() {
		$this->pdo = new PDO(
			'mysql:dbname=pkb08164;host=devweb2011.cis.strath.ac.uk',
			'pkb08164',
			'vagangst'
		);
	
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem('templates');
		$this->twig = new Twig_Environment($loader, array(
			'cache' => 'templates/cache',
		));
		$this->twig->setCache(false);
	}
	
	public function render($name, $context = array()) {
		echo $this->twig->render($name, $context);
	}
}

?>