<?php

class Quiz extends ModelPDO {

	public static function getAllByUser($user) {
		return self::getAllBy(array('user_id' => $user->id));
	}


	public function __construct($data = false) {
		$schema = array(
			'user_id'	 => PDO::PARAM_INT,
			'title'	   => PDO::PARAM_STR,
			'description' => PDO::PARAM_STR,
			'theme_id'	=> PDO::PARAM_INT,
			'created'	 => PDO::PARAM_STR,
			'updated'	 => PDO::PARAM_STR
		);
		parent::__construct($schema, $data);
	}

	public function getQuestions() {
		return Question::getAllByQuiz($this);
	}

	public function getJSONData($depth = 0) {
		$data = parent::getJSONData($depth);
		if (--$depth > 0) {
			$questions = $this->getQuestions();
			if ($questions) {
				foreach ($questions as $question) {
					$data['questions'][] = $question->getJSONData($depth);
				}
			}
		}
		return $data;
	}

}

?>
