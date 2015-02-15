<?php

  // Initialize session
  session_start();

  // Jump to login page if username not set
  if (!isset($_SESSION['username'])) {
        header('Location: ../');
  }

  include ('../lib/dbCon.php');

  $username = $_SESSION['username'];

  $users = ORM::for_table('users')->find_one($username);

  // $course = $users->course;
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

  $type = ORM::for_table('type')->find_one($users->type);

  $placeholder = array("##name##", "##username##", "##type##", "##course##");
  $replace = array($users->name, $username, $type->type, $list_course);

  echo str_replace($placeholder, $replace, file_get_contents('profile'));

?>
