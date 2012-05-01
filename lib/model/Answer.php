<?php

class Answer extends ModelPDO {

	public static function getAllByQuestion($question) {
		return self::getAllBy(array('question_id' => $question->id));
	}

	public static function countByUser($user) {
		return self::countBy(array(
			Quiz::getModelName() => array('user_id' => $user->id),
			Question::getModelName() => 'quiz_id',
			Answer::getModelName() => 'question_id'
		));
	}


	public function __construct() {
		$schema = array(
			'question_id' => PDO::PARAM_INT,
			'text'		=> PDO::PARAM_STR,
			'time'		=> PDO::PARAM_STR
		);
		parent::__construct($schema);
	}

}

?>
