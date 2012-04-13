<?php

class HomeController extends QuizerablesController {

	public function main() {
		if ($this->isLoggedIn()) {
			$this->redirect('createQuiz.php');
		}
		$this->setView('home.html');
	}

}

?>