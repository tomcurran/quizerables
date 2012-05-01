<?php

class UserController extends QuizerablesController {

	const ERR_USER_EMPTY   = 'Please provide a username';
	const ERR_USER_FORMAT  = 'Username can only contain letters, numbers and underscores';
	const ERR_EMAIL_EMPTY  = 'Please provide a email address';
	const ERR_EMAIL_FORMAT = 'Email not in valid format';
	const ERR_PASS_EMPTY   = 'Please provide your current password to change user details';
	const ERR_PASS_INVALID = 'Password entered does not match your current password';
	const ERR_SAVE         = 'Error saving user data';
	const VALID_USERNAME   = 'A-Za-z0-9_';

	public function main() {
		if (!$this->isLoggedIn()) {
			$this->redirect('index.php');
		}
		$this->setView('user.html');

		
		if ($_POST) {
			if (!$this->validCSRF()) {
				$this->addError('Invalid CSRF token');
				return;
			}

			$user = $this->getUser();
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

			$inputError = $this->checkInputs($username, $email, $newPassword, $password);
			$this->addToView('useredit', array(
				'username' => $username,
				'email' => $email,
				'newPassword' => $newPassword
			));
            if ($inputError) {
            	return false;
            }

            if (!$user->validatePassword($password)) {
				$this->addError(self::ERR_PASS_INVALID);
				return;
			}
			$user->name = $username;
			$user->email = $email;
			if (!empty($newPassword)) {
				$user->password = User::hashPassword($newPassword);
			}
			if (!$user->save()) {
				$this->addError(self::ERR_SAVE);
				return;
			}
        }
	}

	private function checkInputs(&$username, &$email, &$newPassword, &$password) {
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
