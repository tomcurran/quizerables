<?php

abstract class QuizerablesController {

	private $twig;
	private $user = NULL;
	private $template;
	private $templateContext = array();
	private $scripts = array();
	private $errors = array();

	public function __construct() {
		session_start();
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
		if(isset($_REQUEST['request'])) {
			$json = $this->async($_REQUEST['request']);
			if (!$json) {
				$json = '{}';
			}
			if (!empty($this->errors)) {
				$json = json_encode(array('errors' => $this->errors));
			}
			return $json;
		}
		$this->main();
		$this->addToView('user', $this->getUser());
		if ($this->isLoggedIn()) {
			$this->addToView('csrf', $_SESSION['csrf']);
		}
		return $this->template ? $this->twig->render($this->template, $this->templateContext) : '';
	}

	public function main() { }
	public function async() { return '{}'; }

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

	public function addError($error) {
		$this->errors[] = $error;
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

	public function validCSRF() {
		if (!$this->isLoggedIn()) {
			return false;
		}
		$requestCSRF = isset($_REQUEST['csrf']) ? $_REQUEST['csrf'] : 'not valid';
		return $requestCSRF == $_SESSION['csrf'];
	}

}

?>
