<?php

class User {

	public $id;
	public $name;
	public $email;

	public function __construct($id) {
		global $lq;
		$sql = 'SELECT * FROM user WHERE user_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$userdata = $sth->fetch(PDO::FETCH_ASSOC);
		
		$this->id = $userdata['user_id'];
		$this->name = $userdata['user_name'];
		$this->email = $userdata['user_email'];
	}
	
	public function getQuizzes() {
		return Quiz::getQuizzesByUser($this->id);
	}
	
	public function save() {
		$sql = 'UPDATE user SET user_name = :name, user_email = :email WHERE user_id = :id';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':id', $this->id, PDO::PARAM_INT);
		$sth->bindParam(':name', $this->name, PDO::PARAM_STR);
		$sth->bindParam(':email', $this->email, PDO::PARAM_STR);
		return $sth->execute();
	}
	
	public static function fromSession() {
		$id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
		return $id ? new User($id) : false ;
		
	}
	
	public static function login($name, $password) {
		global $lq;
		$sql = 'SELECT * FROM user WHERE user_name = :name AND user_password = :password';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':name', $name, PDO::PARAM_STR);
		$sth->bindParam(':password', $password);//, PDO::PARAM_STR);
		$sth->execute();
		if($userdata = $sth->fetch(PDO::FETCH_ASSOC)) {
			$id = $userdata['user_id'];
			$_SESSION['user_id'] = $id;
			return new User($id);
		} else {
			return false;
		}
	}
	
	public static function logout() {
		session_unset();
		session_destroy();
		session_write_close();
		setcookie(session_name(),'',0,'/');
		session_regenerate_id(true);
	}

	public static function signup($name, $email, $password) {
		global $lq;
		$sql = 'INSERT INTO user (user_name, user_email, user_password) VALUES (:name, :email, :password)';
		$sth = $lq->pdo->prepare($sql);
		$sth->bindParam(':name', $name, PDO::PARAM_STR);
		$sth->bindParam(':email', $email, PDO::PARAM_STR);
		$sth->bindParam(':password', $password, PDO::PARAM_STR);
		return $sth->execute();
	}
		
}

?>