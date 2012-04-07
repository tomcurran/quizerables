<?php

class Quiz {
	
	public $twig;
	public $db;
	public $user;
	
	public function __construct() {
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem('templates');
		$this->twig = new Twig_Environment($loader, array(
			'cache' => 'templates/cache',
		));
		$this->twig->setCache(false);
		
		$this->db = new DB();		
	}
	
	public function render($name, $context = array()) {
		echo $this->twig->render($name, $context);
	}
}

?>