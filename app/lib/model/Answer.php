<?php

class Answer extends ModelPDO {

	public static function getAllByQuestion($question) {
		return self::getAllBy(array('question_id' => $question->id));
	}

	public static function countByUser($user) {
		$bindName = self::getBindName('user_id');
		$q = 'SELECT COUNT(*) FROM quizs, questions, answers';
		$q .= " WHERE quiz_user_id = {$bindName}";
		$q .= ' AND question_quiz_id = quiz_id';
		$q .= ' AND answer_question_id = question_id';
		$sth = self::getPDO()->prepare($q);
		$sth->bindValue($bindName, $user->id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchColumn();
	}


	public function __construct(array $data = NULL) {
		$schema = array(
			'question_id' => PDO::PARAM_INT,
			'text'		=> PDO::PARAM_STR,
			'time'		=> PDO::PARAM_STR
		);
		parent::__construct($schema, $data);
	}

}

?>
