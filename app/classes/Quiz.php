<?php

class Quiz {
	
	public $id;
	public $title;
	public $description;
	public $themeid;
	
	public function __construct($id) {
		global $lq;
		$sql = 'SELECT * FROM quiz WHERE quiz_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$quizdata = $sth->fetch(PDO::FETCH_ASSOC);
		
		$this->id = $quizdata['quiz_id'];
		$this->title = $quizdata['quiz_title'];
		$this->description = $quizdata['quiz_description'];
		$this->themeid = $quizdata['quiz_theme_id'];
	}
	
	public function delete() {
		global $lq;
		$sql = 'DELETE FROM quiz WHERE quiz_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':id', $this->id, PDO::PARAM_INT);
		if ($sth->execute()) {
			unset($this->id);
			unset($this->title);
			unset($this->description);
			return true;
		} else {
			return false;
		}
	}
	
	public function getQuestions() {
		return Question::getQuestionsByQuiz($this->id);
	}
	
	public function save() {
		$sql = 'UPDATE quiz SET quiz_title = :title, quiz_description = :description WHERE quiz_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':title', $this->title, PDO::PARAM_STR);
		$sth->bindParam(':description', $this->description, PDO::PARAM_STR);
		return $sth->execute();
	}
	
	public static function createQuiz($userid, $title, $description = '') {
		global $lq;
		$sql = 'INSERT INTO quiz (quiz_user_id, quiz_title, quiz_description) VALUES (:userid, :title, :description)';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':userid', $userid, PDO::PARAM_INT);
		$sth->bindParam(':title', $title, PDO::PARAM_STR);
		$sth->bindParam(':description', $description, PDO::PARAM_STR);
		return $sth->execute();
	}
	
	public static function getQuizzesByUser($userid) {
		global $lq;
		$sql = 'SELECT quiz_id FROM quiz WHERE quiz_user_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':id', $userid, PDO::PARAM_INT);
		$sth->execute();
		$quizids = $sth->fetchAll(PDO::FETCH_COLUMN);
		$quizzes = array();
		foreach ($quizids as $quizid) {
			$quizzes[] = new Quiz($quizid);
		}
		return $quizzes;
	}
	
}

?>