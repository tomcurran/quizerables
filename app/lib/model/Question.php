<?php

class Question extends ModelPDO {

	public static function getAllByQuiz($quiz) {
		return self::getAllBy(array('quiz_id' => $quiz->id));
	}

	public static function countByUser($user) {
		return self::countBy(array(
			Quiz::getModelName() => array('user_id' => $user->id),
			Question::getModelName() => 'quiz_id'
		));
	}


	public function __construct() {
		$schema = array(
			'quiz_id'  => PDO::PARAM_INT,
			'text'	 => PDO::PARAM_STR,
			'required' => PDO::PARAM_BOOL
		);
		parent::__construct($schema);
	}

	public function getAnswers() {
		return Answer::getAllByQuestion($this);
	}

	protected function getChildData($depth) {
		$data = NULL;
		foreach ($this->getAnswers() as $answer) {
			$data['answers'][] = $answer->getData($depth);
		}
		return $data;
	}

}

?>
