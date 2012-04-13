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
			return '{"error": "Invalid authenication"}';
		}
		if (!$this->validCSRF()) {
			return '{"error": "Invalid CSRF token"}';
		}
		$user = $this->getUser();
		switch ($request) {
			case 'createQuiz':
				$quiz = new Quiz();
				$quiz->title = $_REQUEST['quizTitle'];
				$quiz->user_id = $user->id;
				if (!$quiz->save()) {
					return '{"error": "Problem saving quiz"}';
				}
				return $quiz->encodeJSON();
				break;
			case 'deleteQuiz':
				$quiz = Quiz::get($_REQUEST['quizId']);
				if (!$quiz) {
					return '{"error": "Quiz does not exist"}';
				}
				if ($quiz->user_id != $user->id) {
					return '{"error": "Quiz belongs to another user"}';
				}
				if (!$quiz->delete()) {
					return '{"error": "Problem deleting quiz"}';
				}
				break;
			case 'loadQuizs':
				$quizs = Quiz::getAllByUser($user);
				return Quiz::encodeAllJSON($quizs);
				break;
		}
	}

}

?>
