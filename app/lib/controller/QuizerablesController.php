<?php

abstract class QuizerablesController {

	private $twig;
	private $user = NULL;
	private $template;
	private $templateContext = array();
	private $scripts = array();

	public function __construct() {
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem('lib/view');
		$this->twig = new Twig_Environment($loader, array(
			'cache' => 'lib/view/cache',
			'strict_variables' => false
		));
		$this->twig->setCache(false);
		$this->user = User::fromSession();
	}

	public function control() {
		session_start();
		if(isset($_REQUEST['request'])) {
			$this->async($_REQUEST['request']);
			return;
		}
		$this->main();
		$this->addToView('user', $this->getUser());
		$this->render();
	}

	public function main() { }
	public function async() { }

	public function isLoggedIn() {
		$user = $this->getUser();
		return isset($user);
	}

	public function getUser() {
		if (!$this->user) {
			if (!$this->user = User::fromSession()) {
				$this->user = NULL;
			}
		}
		return $this->user;
	}

	public function addScript($script) {
		if (empty($this->templateContext['scripts'])) {
			$this->templateContext['scripts'][] = 'jquery.js';
		}
		$this->templateContext['scripts'][] = $script;
	}

	public function redirect($page) {
		header("Location: {$page}");
	}

	public function addToView($name, $value) {
		$this->templateContext[$name] = $value;
	}

	public function setView($template) {
		$this->template = $template;
	}

	public function render() {
		if ($this->template) {
			echo $this->twig->render($this->template, $this->templateContext);
		}
	}

}

?>