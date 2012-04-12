<?php

class LogoutController extends QuizerablesController {

	public function main() {
	    if ($this->validCSRF()) {
		    User::logout();
		}
		$this->redirect('index.php');
	}

}

?>
