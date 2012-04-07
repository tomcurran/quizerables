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
	
	public function getUserData($id) {
		$id = $this->sql->real_escape_string($id);
        $query = "SELECT * FROM user WHERE user_id='{$id}'";

        if ($result = $this->sql->query($query)) {
            $userdata = $result->fetch_array();
            $result->close();
        }
        
        return empty($userdata) ? array() : $userdata;
    }

    public function userAuthenticated($username, $password) {
		$username = $this->sql->real_escape_string($username);
		$password = hash('sha256', $this->sql->real_escape_string($password) . 'salt....');
        $query = "SELECT * FROM blog_user WHERE user_name='{$username}' AND user_password='{$password}'";
        $result = $this->sql->query($query);
        $auth = false;

        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_array();
            $auth = $result['user_id'];
			$result->close();
        }

        return $auth;
    }

	function insertUser($username, $email, $password) {
		$username = $this->sql->real_escape_string($username);
		$email = $this->sql->real_escape_string($email);
		$password = hash('sha256', $this->sql->real_escape_string($password) . 'salt....');
        $query = "INSERT INTO user (user_name, user_email, user_password) VALUES ('{$username}', '{$email}', '{$password}')";
        return $this->sql->query($query);
	}
	
}
?>