<?php

class EditQuizController extends QuizerablesController {

	public function main() {
		if (!$this->isLoggedIn()) {
			$this->redirect('index.php');
		}
		$user = $this->getUser();
		if(isset($_REQUEST['id'])) {
			$quiz = $this->getQuiz($_REQUEST['id']);
		} else {
			$quiz = new Quiz();
			$quiz->user_id = $user->id;
			$this->saveQuiz($quiz);
		}
		$this->setView('editQuiz.html');
		$this->addToView('quiz', $quiz);
		$this->addScript('editQuiz.js');
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
				return $quiz->encodeJSON(1);
				break;
			case 'saveQuiz':
				$quiz->title = $_REQUEST['title'];
				$quiz->description = $_REQUEST['description'];
				$this->saveQuiz($quiz);
				break;
			case 'saveQuestion':
				$question = $this->getQuestion($_REQUEST['questionId'], $quiz);
				if (!$question) {
					return;
				}
				$question->text = $_REQUEST['questionText'];
				$question->required = $_REQUEST['questionRequired'];
				$this->saveQuestion($question);
				break;
			case 'createQuestion':
				$question = new Question();
				$question->quiz_id = $quiz->id;
				if (!$this->saveQuestion($question)) {
					return;
				}
				return $question->encodeJSON();
				break;
			case 'deleteQuestion':
				$question = $this->getQuestion($_REQUEST['questionId'], $quiz);
				if (!$question) {
					return;
				}
				$this->deleteQuestion($question);
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

	private function getQuestion($id, $quiz) {
		$question = Question::get($id);
		if (!$question) {
			$this->addError('Question does not exist');
			return false;
		}
		if ($question->quiz_id != $quiz->id) {
			$this->addError('Question belongs to another quiz');
			return false;
		}
		return $question;
	}

	private function saveQuestion($question) {
		if (!$question->save()) {
			$this->addError('Problem saving question');
			return false;
		}
		return true;
	}

	private function deleteQuestion($question) {
		if (!$question->delete()) {
			$this->addError('Problem deleting question');
			return false;
		}
		return true;
	}

}

?>
