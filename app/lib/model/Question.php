<?php

class Question extends ModelPDO {

	public static function getAllByQuiz($quiz) {
		return self::getAllBy(array('quiz_id' => $quiz->id));
	}

	public static function countByUser($user) {
		$bindName = self::getBindName('user_id');
		$q = 'SELECT COUNT(*) FROM quizs, questions';
		$q .= " WHERE quiz_user_id = {$bindName}";
		$q .= ' AND question_quiz_id = quiz_id';
		$sth = self::getPDO()->prepare($q);
		$sth->bindValue($bindName, $user->id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchColumn();
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
