<?php

class User extends ModelPDO {

	public static function fromSession() {
		if (isset($_SESSION['user_id']) && isset($_SESSION['csrf'])) {
			$user = self::get($_SESSION['user_id']);
			return $user ? $user : false;
		}
		return false;
	}

	public static function login($name, $password) {
		$user = self::getBy(array('name' => $name));
		if ($user && $user->validatePassword($password)) {
			session_regenerate_id();
			$_SESSION['user_id'] = $user->id;
			$_SESSION['csrf'] = User::generateSalt();
			return $user;
		}
		return NULL;
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
		$user->password = self::hashPassword($password);
		return $user->save();
	}

	// crackstation.net/hashing-security.html
	public static function hashPassword($password) {
		$salt = User::generateSalt();
		$hash = hash('sha256', $salt . $password);
		return $salt . $hash;
	}

	private static function generateSalt() {
		return bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
	}

	public function __construct(array $data = NULL) {
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

	public function validatePassword($password) {
		$salt = substr($this->password, 0, 64);
		$validHash = substr($this->password, 64, 64);
		$testHash = hash('sha256', $salt . $password);
		return $testHash === $validHash;
	}

	protected function getChildData($depth) {
		$data = NULL;
		foreach ($this->getQuizs() as $quiz) {
			$data['quizs'][] = $quiz->getData($depth);
		}
		return $data;
	}

}

?>
