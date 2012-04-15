<?php

class HomeController extends QuizerablesController {

	public function main() {
		if ($this->isLoggedIn()) {
			$this->redirect('createQuiz.php');
		}
		$this->setView('home.html');
		$this->addToView('statistics', array(
			'users' => User::countAll(),
			'quizs' => Quiz::countAll(),
			'questions' => Question::countAll(),
			'answers' => Answer::countAll()
		));
	}

}

?>
