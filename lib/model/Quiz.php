<?php

class Quiz extends ModelPDO {

	public static function getAllByUser($user) {
		return self::getAllBy(array('user_id' => $user->id));
	}

	public static function countByUser($user) {
		return self::countBy(array(
			Quiz::getModelName() => array('user_id' => $user->id)
		));
	}


	public function __construct() {
		$schema = array(
			'user_id'	 => PDO::PARAM_INT,
			'title'	   => PDO::PARAM_STR,
			'description' => PDO::PARAM_STR,
			'theme_id'	=> PDO::PARAM_INT,
			'created'	 => PDO::PARAM_STR,
			'updated'	 => PDO::PARAM_STR
		);
		parent::__construct($schema);
	}

	public function getQuestions() {
		return Question::getAllByQuiz($this);
	}

	protected function getChildData($depth) {
		$data = NULL;
		foreach ($this->getQuestions() as $question) {
			$data['questions'][] = $question->getData($depth);
		}
		return $data;
	}

}

?>
