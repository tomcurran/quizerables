<?php

class LogoutController extends QuizerablesController {

	public function main() {
		User::logout();
		$this->redirect('index.php');
	}

}

?>