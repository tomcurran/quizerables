<?php

class DoQuizController extends QuizerablesController {

	const ERR_INVALID_QUESION = 'A question was found not to exist';
	const ERR_QUESTION_REQUIRED = 'A required question was found to be missing';

	public function main() {
		$this->setView('doQuiz.html');
		$this->addScript('jquery.validate.js');
		$this->addScript('doQuiz.js');
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$quiz = Quiz::get($id);
		if ($quiz) {
			$this->addToView('quiz', $quiz);
			$this->addToView('questions', $quiz->getQuestions());

		    if ($_POST) {
		    	
		    	$questions = isset($_POST['questions']) ? $_POST['questions'] : array();
		    	$answers = isset($_POST['answers']) ? $_POST['answers'] : array();

		    	$inputError = $this->checkInputs($quiz, $questions, $answers);
				$this->addToView('answers', $answers);
		        if ($inputError) {
		        	return false;
		        }

				for ($i = 0, $size = sizeof($questions); $i < $size; ++$i) {
					$question = Question::get($questions[$i]);
					$a = new Answer();
					$a->question_id = $question->id;
					$a->text = $answers[$i];
					$a->save();
				}
				$this->setView('doQuizDone.html');
		    }
		}
	}

	private function checkInputs(&$quiz, &$questions, &$answers) {
		$error = false;
		
		for ($i = 0, $size = sizeof($questions); $i < $size; ++$i) {
			$question = Question::get($questions[$i]);
			if (!$question && array_key_exists($i, $answers)) {
				unset($quesion[$i]);
				unset($answers[$i]);
				continue;
			}
			if (!array_key_exists($i, $answers)) {
				unset($quesion[$i]);
				continue;
			}
			if ($question->quiz_id != $quiz->id) {
				unset($quesion[$i]);
				unset($answers[$i]);
				continue;
			}
			$answer = $answers[$i];
			if ($question->required && empty($answer)) {
				$this->addError(self::ERR_QUESTION_REQUIRED);
				$error = true;
				continue;
			}
		}

		return $error;
	}

}

?>
