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
			echo '{"error": "not logged in"}';
			return;
		}
		if (!$this->validCSRF()) {
			echo '{"error": "invalid csrf"}';
			return;
		}
		$quiz = Quiz::get($_REQUEST['id']);
		if ($quiz->user_id != $this->getUser()->id) {
			echo '{"error": "not your quiz to edit"}';
			return;
		}
		switch ($requset) {
			case 'loadQuiz':
				echo $quiz->encodeJSON(1);
				break;
			case 'saveQuiz':
				$quiz->title = $_REQUEST['title'];
				$quiz->description = $_REQUEST['description'];
				$quiz->save();
				break;
			case 'saveQuestion':
				$question = Question::get($_REQUEST['questionId']);
				$question->text = $_REQUEST['questionText'];
				$question->required = $_REQUEST['questionRequired'];
				$question->save();
				break;
			case 'createQuestion':
				$question = new Question();
				$question->quiz_id = $quiz->id;
				$question->save();
				echo $question->encodeJSON();
				break;
			case 'deleteQuestion':
				$question = Question::get($_REQUEST['questionId']);
				$question->delete();
				break;
		}
	}

}

?>
