<?php

class User extends ModelPDO {

	public static function fromSession() {
		if (isset($_SESSION['user_id'])) {
			$user = User::get($_SESSION['user_id']);
			return $user ? $user : false;
		}
		return false;
	}

	public static function login($name, $password) {
		$user = User::getBy('name', $name);
		if ($user && $user->password == $password) {
			$_SESSION['user_id'] = $user->id;
			return $user;
		}
		return false;
	}

	public static function logout() {
		session_unset();
		session_destroy();
		session_write_close();
		setcookie(session_name(),'',0,'/');
		session_regenerate_id(true);
	}

	public static function signup($name, $email, $password) {
		$user = new User();
		$user->name = $name;
		$user->email = $email;
		$user->password = $password;
		return $user->save();
	}


	public function __construct($data = false) {
		$schema = array(
			'name'	 => PDO::PARAM_STR,
			'email'	=> PDO::PARAM_STR,
			'password' => PDO::PARAM_STR
		);
		parent::__construct($schema, $data);
	}

	public function getQuizs() {
		return Quiz::getAllByUser($this);
	}

}

?>