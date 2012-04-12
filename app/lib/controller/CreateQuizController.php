<?php

class CreateQuizController extends QuizerablesController {

	public function main() {
		if (!$this->isLoggedIn()) {
			$this->redirect('index.php');
		}
		$this->setView('createQuiz.html');
		$this->addScript('createQuiz.js');
	}

	public function async($request) {
		if (!$this->isLoggedIn()) {
			echo '{"error": "not logged in"}';
			return;
		}
		switch ($request) {
			case 'createQuiz':
				$quiz = new Quiz();
				$quiz->title = $_REQUEST['quizTitle'];
				$quiz->user_id = $this->getUser()->id;
				$quiz->save();
				echo $quiz->encodeJSON();
				break;
			case 'deleteQuiz':
				$quiz = Quiz::get($_REQUEST['quizId']);
				$quiz->delete();
				break;
			case 'loadQuizs':
				$quizs = Quiz::getAllByUser($this->getUser());
				echo Quiz::encodeAllJSON($quizs);
				break;
		}
	}

}

?>
