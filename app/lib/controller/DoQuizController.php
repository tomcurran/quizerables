<?php

class DoQuizController extends QuizerablesController {

	public function main() {
		if ($_POST) {
			$this->setView('doQuizDone.html');
			/* save answers */
		} else {
			$this->setView('doQuiz.html');
			$quiz = Quiz::get($_GET['id']);
			$this->addToView('quiz', $quiz);
			$this->addToView('questions', $quiz->getQuestions());
		}
	}

}

?>