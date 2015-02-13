<?php

  // Initialize session
  session_start();

  // Jump to login page if username not set
  if (!isset($_SESSION['username'])) {
        header('Location: ../');
  }

  include ('../lib/dbCon.php');

  $username = $_SESSION['username'];

  $user = ORM::for_table('user')->find_one($username);

  $placeholder = array("##name##", "##username##", "##type##", "##course##");
  $replace = array($user->name, $user->username, $user->type, $user->course);

  echo str_replace($placeholder, $replace, file_get_contents('profile'));

?>
