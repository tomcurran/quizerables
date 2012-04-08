<?php

require_once('init.php');

User::logout();

header('Location: index.php');

?>