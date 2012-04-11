<?php

class Question extends ModelPDO {

	public static function getAllByQuiz($quiz) {
		return self::getAllBy(array('quiz_id' => $quiz->id));
	}


	public function __construct(array $data = NULL) {
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

	protected function getChildData($depth) {
		$data = NULL;
		foreach ($this->getAnswers() as $answer) {
			$data['answers'][] = $answer->getData($depth);
		}
		return $data;
	}

}

?>
