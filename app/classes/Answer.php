<?php

class Answer {
	
	public $id;
	public $text;
	
	public function __construct($id) {
		global $lq;
		$sql = 'SELECT * FROM answer WHERE answer_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':id', $id,  PDO::PARAM_INT);
		$sth->execute();
		$ansdata = $sth->fetch(PDO::FETCH_ASSOC);
		
		$this->id = $ansdata['answer_id'];
		$this->text = $ansdata['answer_text'];
	}
	
	public function delete() {
		global $lq;
		$sql = 'DELETE FROM answer WHERE answer_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':id', $this->id, PDO::PARAM_INT);
		if ($sth->execute()) {
			unset($this->id);
			unset($this->text);
			return true;
		} else {
			return false;
		}
	}
	
	
	public function save() {
		$sql = 'UPDATE answer SET answer_text = :text WHERE answer_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':text', $this->text, PDO::PARAM_STR);

		return $sth->execute();
	}
	
	public static function createAnswer($questid, $text) {
		global $lq;
		$sql = 'INSERT INTO answer (answer_quest_id, answer_text) VALUES (:questid, :text)';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':questid', $questid, PDO::PARAM_INT);
		$sth->bindParam(':text', $text, PDO::PARAM_STR);
		return $sth->execute();
	}
	
	public static function getAnswersByQuestion($questid) {
		global $lq;
		$sql = 'SELECT answer_id FROM answer WHERE answer_quest_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':id', $questid, PDO::PARAM_INT);
		$sth->execute();
		$ansids = $sth->fetchAll(PDO::FETCH_COLUMN);
		$answers = array();
		foreach ($ansids as $ansid) {
			$answers[] = new Answer($ansid);
		}
		return $answers;
	}
	
}

?>