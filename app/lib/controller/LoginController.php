<?php

class LoginController extends QuizerablesController {

	const ERR_USER_EMPTY  = 'Please provide a username';
	const ERR_USER_FORMAT = 'Username can only contain letters, numbers and underscores';
	const ERR_PASS_EMPTY  = 'Please provide a password';
	const ERR_LOGIN_FAIL  = 'The username and password combination failed to authenticate';
	const VALID_USERNAME  = 'A-Za-z0-9_';

	public function main() {
		if ($this->isLoggedIn()) {
			$this->redirect('index.php');
			return;
		}
		$this->setView('login.html');

        if ($_POST) {
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

			$inputError = $this->checkInputs($username, $password);
			$this->addToView('login', array(
				'username' => $username
			));
            if ($inputError) {
            	return false;
            }

            if (User::login($username, $password)) {
			    $this->redirect('createQuiz.php');
            } else {
                $this->addError(self::ERR_LOGIN_FAIL);
                return false;
            }
        }
	}

	private function checkInputs(&$username, &$password) {
		$error = false;
		if (empty($username)) {
			$this->addError(self::ERR_USER_EMPTY);
			$error = true;
		} elseif (!preg_match('/^[' . self::VALID_USERNAME . ']+$/', $username)) {
			$username = preg_replace('/[^' . self::VALID_USERNAME . ']/', '', $username);
			$this->addError(self::ERR_USER_FORMAT);
			$error = true;
		}
		if (empty($password)) {
			$this->addError(self::ERR_PASS_EMPTY);
			$error = true;
		}
		return $error;
	}

}

?>
