<?php

class Question extends ModelPDO {

	public static function getAllByQuiz($quiz) {
		return self::getAllBy(array('quiz_id' => $quiz->id));
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

	protected function getJSONData($depth = 0) {
		$data = parent::getJSONData($depth);
		if (--$depth > 0) {
			$answers = $this->getAnswers();
			if ($answers) {
				foreach ($answers as $answer) {
					$data['answers'][] = $answer->getJSONData($depth);
				}
			}
		}
		return $data;
	}

}

?>
