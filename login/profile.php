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

  $course = $user->course;
  $list_course = "";

  if (empty($course)){
   $list_course = 'No Course';
  }
  else {
   $course = explode("|",$course);
   sort($course);
    for ($i=0;$i<count($course);$i++) {
      $courseName = $course[$i];
      $list_course = $list_course . "$courseName<br/>";
      }
  }

  $placeholder = array("##name##", "##username##", "##type##", "##course##");
  $replace = array($user->name, $user->username, $user->type, $list_course);

  echo str_replace($placeholder, $replace, file_get_contents('profile'));

?>
