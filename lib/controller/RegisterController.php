 <?php

class RegisterController extends QuizerablesController {

	const ERR_USER_EMPTY    = 'Please provide a username';
	const ERR_USER_FORMAT   = 'Username can only contain letters, numbers and underscores';
	const ERR_EMAIL_EMPTY   = 'Please provide a email address';
	const ERR_EMAIL_FORMAT  = 'Email not in valid format';
	const ERR_PASS_EMPTY    = 'Please provide a password';
	const ERR_LOGIN_FAIL    = 'The username and password combination failed to authenticate';
	const ERR_REGISTER_FAIL = 'Registration failed. Please choose a different username.';
	const VALID_USERNAME    = 'A-Za-z0-9_';

	public function main() {
		if ($this->isLoggedIn()) {
			$this->redirect('index.php');
			return;
		}
		$this->setView('register.html');

        if ($_POST) {
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

			$inputError = $this->checkInputs($username, $email, $password);
			$this->addToView('register', array(
				'username' => $username,
				'email' => $email
			));
            if ($inputError) {
            	return false;
            }

            if (User::signup($username, $email, $password)) {
            	User::login($username, $password);
			    $this->redirect('createQuiz.php');
            } else {
                $this->addError(self::ERR_REGISTER_FAIL);
                return false;
            }
        }
	}

	private function checkInputs(&$username, &$email, &$password) {
		$error = false;
		if (empty($username)) {
			$this->addError(self::ERR_USER_EMPTY);
			$error = true;
		} elseif (!preg_match('/^[' . self::VALID_USERNAME . ']+$/', $username)) {
			$username = preg_replace('/[^' . self::VALID_USERNAME . ']/', '', $username);
			$this->addError(self::ERR_USER_FORMAT);
			$error = true;
		}
		if (empty($email)) {
			$this->addError(self::ERR_EMAIL_EMPTY);
			$error = true;
		} elseif (filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE) {
			$email = filter_var($email, FILTER_SANITIZE_EMAIL);
			$this->addError(self::ERR_EMAIL_FORMAT);
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
