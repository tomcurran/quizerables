<?php

class Question extends ModelPDO {

	public static function getAllByQuiz($quiz) {
		return self::getBy(array('quiz_id' => $quiz->id));
	}


	public function __construct($data = false) {
		$schema = array(
			'quiz_id'  => PDO::PARAM_INT,
			'text'	 => PDO::PARAM_STR,
			'required' => PDO::PARAM_BOOL
		);
		parent::__construct($schema, $data);
	}

	public function getAnswers() {
		return Answer::getAllByQuestion($this);
	}

}

?>
