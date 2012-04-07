<?php

class User {

	public $id;
	public $name;
	public $email;

	public function __construct($userdata) {
		$this->id = $userdata['user_id'];
		$this->name = $userdata['user_name'];
		$this->email = $userdata['user_email'];
	}
	
}

?>