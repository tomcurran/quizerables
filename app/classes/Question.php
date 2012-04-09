<?php

class Question {
	
	public $id;
	public $text;
	public $required;
	
	public function __construct($id) {
		global $lq;
		$sql = 'SELECT * FROM question WHERE quest_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':id', $id,  PDO::PARAM_INT);
		$sth->execute();
		$questdata = $sth->fetch(PDO::FETCH_ASSOC);
		
		$this->id = $questdata['quest_id'];
		$this->text = $questdata['quest_text'];
		$this->required = $questdata['quest_required'];
	}
	
	public function delete() {
		global $lq;
		$sql = 'DELETE FROM quest WHERE quest_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':id', $this->id, PDO::PARAM_INT);
		if ($sth->execute()) {
			unset($this->id);
			unset($this->text);
			unset($this->required);
			return true;
		} else {
			return false;
		}
	}
	
	public function getAnswers() {
		return Answer::getAnswersByQuestion($this->id);
	}
	
	public function save() {
		$sql = 'UPDATE quest SET quest_text = :text, quest_required = :required WHERE quest_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':text', $this->text, PDO::PARAM_STR);
		$sth->bindParam(':required', $this->description, PDO::PARAM_BOOL);

		return $sth->execute();
	}
	
	public static function createQuestion($quizid, $text, $required) {
		global $lq;
		$sql = 'INSERT INTO question (quest_quiz_id, quest_text, quest_required) VALUES (:quizid, :text, :required)';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':quizid', $quizid, PDO::PARAM_INT);
		$sth->bindParam(':text', $text, PDO::PARAM_STR);
		$sth->bindParam(':required', $required, PDO::PARAM_STR);
		return $sth->execute();
	}
	
	public static function getQuestionsByQuiz($quizid) {
		global $lq;
		$sql = 'SELECT quest_id FROM question WHERE quest_quiz_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':id', $quizid, PDO::PARAM_INT);
		$sth->execute();
		$questids = $sth->fetchAll(PDO::FETCH_COLUMN);
		$questions = array();
		foreach ($questids as $questid) {
			$questions[] = new Question($questid);
		}
		return $questions;
	}
	
}

?>