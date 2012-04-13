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
			$this->addError('Invalid authenication');
			return;
		}
		if (!$this->validCSRF()) {
			$this->addError('Invalid CSRF token');
			return;
		}
		$user = $this->getUser();
		switch ($request) {
			case 'createQuiz':
				$quiz = new Quiz();
				$quiz->title = $_REQUEST['quizTitle'];
				$quiz->user_id = $user->id;
				if (!$quiz->save()) {
					return;
				}
				return $quiz->encodeJSON();
				break;
			case 'deleteQuiz':
				$quiz = $this->getQuiz($_REQUEST['quizId']);
				if (!$quiz) {
					return;
				}
				$this->deleteQuiz($quiz);
				break;
			case 'loadQuizs':
				$quizs = Quiz::getAllByUser($user);
				return Quiz::encodeAllJSON($quizs);
				break;
		}
	}

	private function getQuiz($id) {
		$quiz = Quiz::get($id);
		if (!$quiz) {
			$this->addError('Quiz does not exist');
			return false;
		}
		if ($quiz->user_id != $this->getUser()->id) {
			$this->addError('Quiz belongs to another user');
			return false;
		}
		return $quiz;
	}

	private function saveQuiz($quiz) {
		if (!$quiz->save()) {
			$this->addError('Problem saving quiz');
			return false;
		}
	}

	private function deleteQuiz($quiz) {
		if (!$quiz->delete()) {
			$this->addError('Problem deleting quiz');
			return false;
		}
	}

}

?>
