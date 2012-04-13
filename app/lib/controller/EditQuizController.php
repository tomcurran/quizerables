<?php

class EditQuizController extends QuizerablesController {

	public function main() {
		if (!$this->isLoggedIn()) {
			$this->redirect('index.php');
		}
		$user = $this->getUser();
		if(isset($_REQUEST['id'])) {
			$quiz = Quiz::get($_REQUEST['id']);
			if ($quiz->user_id != $user->id) {
				die('GET THE FUCK OOT!');
			}
		} else {
			$quiz = new Quiz();
			$quiz->user_id = $user->id;
			$quiz->save();
		}
		$this->setView('editQuiz.html');
		$this->addToView('quiz', $quiz);
		$this->addScript('editQuiz.js');
	}

	public function async($requset) {
		if (!$this->isLoggedIn()) {
			return '{"error": "Invalid authenication"}';
		}
		if (!$this->validCSRF()) {
			return '{"error": "Invalid CSRF token"}';
		}
		$quiz = Quiz::get($_REQUEST['id']);
		if (!$quiz) {
			return '{"error": "Quiz does not exist"}';
		}
		if ($quiz->user_id != $this->getUser()->id) {
			return '{"error": "Quiz belongs to another user"}';
		}
		switch ($requset) {
			case 'loadQuiz':
				return $quiz->encodeJSON(1);
				break;
			case 'saveQuiz':
				$quiz->title = $_REQUEST['title'];
				$quiz->description = $_REQUEST['description'];
				if (!$quiz->save()) {
					return '{"error": "Problem saving quiz"}';
				}
				break;
			case 'saveQuestion':
				$question = Question::get($_REQUEST['questionId']);
				$question->text = $_REQUEST['questionText'];
				$question->required = $_REQUEST['questionRequired'];
				if (!$question->save()) {
					return '{"error": "Problem saving question"}';
				}
				break;
			case 'createQuestion':
				$question = new Question();
				$question->quiz_id = $quiz->id;
				if (!$question->save()) {
					return '{"error": "Problem saving question"}';
				}
				return $question->encodeJSON();
				break;
			case 'deleteQuestion':
				$question = Question::get($_REQUEST['questionId']);
				if (!$question) {
					return '{"error": "Question does not exist"}';
				}
				if ($question->quiz_id != $quiz->id) {
					return '{"error": "Question belongs to another quiz"}';
				}
				if (!$question->delete()) {
					return '{"error": "Problem deleting question"}';
				}
				break;
		}
	}

}

?>
