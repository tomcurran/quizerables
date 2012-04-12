<?php

class HomeController extends QuizerablesController {

	public function main() {
		$this->setView('home.html');
		if ($this->isLoggedIn()) {
			$this->redirect('createQuiz.php');
		}
	}

}

?>