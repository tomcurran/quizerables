<?php

class ViewAnswersController extends QuizerablesController {
	
	public function main() {
		if (!$this->isLoggedIn()) {
			$this->redirect('index.php');
		}
		$this->setView('viewAnswers.html');
		$this->addScript('viewAnswer.js');
		$user = $this->getUser();
		if (isset($_REQUEST['id'])) {
			$quiz = $this->getQuiz($_REQUEST['id']);
			$this->addToView('quiz', $quiz);
		}

		
	}
	
	public function async($requset) {
		if (!$this->isLoggedIn()) {
			$this->addError('Invalid authenication');
			return;
		}
		if (!$this->validCSRF()) {
			$this->addError('Invalid CSRF token');
			return;
		}
		$quiz = $this->getQuiz($_REQUEST['id']);
		if (!$quiz) {
			return;
		}
		switch ($requset) {
			case 'loadQuiz':
				return $quiz->encodeJSON(2);
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
	
}

?>
