<?php

class DB {

	private $sql;
	
    public function __construct() {
        $this->sql = new mysqli('devweb2011.cis.strath.ac.uk', 'pkb08164', 'vagangst', 'pkb08164');

        if ($this->sql->connect_error) {
            die('Connect Error (' . $this->sql->connect_errno . ') ' . $this->sql->connect_error);
        }
    }

	public function answerQuestion($questID, $questText) {
		$escapedID = $this->sql->real_escape_string($questID);
		$escapedText = $this->sql->real_escape_string($questText);
		$query = "INSERT INTO answer (answer_quest_id, answer_text) VALUES ({$escapedID}, '{$escapedText}')";
		return $this->sql->query($query);
	}
	
    public function close() {
        $this->sql->close();
    }
	
	public function getQuestionnaire($naireID) {
		$escaped = $this->sql->real_escape_string($naireID);
		$query = "SELECT * FROM questionnaire WHERE naire_id = $escaped";
		$results = $this->sql->query($query);
		
		return $results ? $results->fetch_array() : array();
	}
	
	public function getQuestions($naireID) {
		$escaped = $this->sql->real_escape_string($naireID);
		$query = "SELECT * FROM question WHERE quest_naire_id = $escaped";
		$results = $this->sql->query($query);
		
		if ($results) {
			$arr = array();
			while ($row = $results->fetch_array()) {
				$arr[] = $row;
			}
			return $arr;
		} else {
			return array();
		}
	}
	
}
?>