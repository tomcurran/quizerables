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
		if (!$this->validCSRF()) {
			echo '{"error": "invalid csrf"}';
			return;
		}
		$user = $this->getUser();
		switch ($request) {
			case 'createQuiz':
				$quiz = new Quiz();
				$quiz->title = $_REQUEST['quizTitle'];
				$quiz->user_id = $user->id;
				$quiz->save();
				echo $quiz->encodeJSON();
				break;
			case 'deleteQuiz':
				$quiz = Quiz::get($_REQUEST['quizId']);
				if ($quiz->user_id == $user->id) {
					$quiz->delete();
				}
				break;
			case 'loadQuizs':
				$quizs = Quiz::getAllByUser($user);
				echo Quiz::encodeAllJSON($quizs);
				break;
		}
	}

}

?>
