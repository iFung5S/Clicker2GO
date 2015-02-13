<?php
// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: ../');
}
  $username = $_SESSION['username'];
  include_once ('../lib/dbCon.php');

  $courseName = $_POST['courseName'];
  if (preg_match("/^[a-zA-Z0-9]*$/",$courseName)) {
    $user = ORM::for_table('user')->find_one($username);
    $course = $user->course;
    
    if (empty($course)) {
      $course = $courseName; }
    else { 
      $course_arr = explode("|",$course);
      sort($course_arr);
      if (!in_array($courseName,$course_arr))
      { $course = implode("|",array($course,$courseName)); }
    }      
    $user->course = $course;
    $user->save();

    $redirect = "<script>window.location.assign('../index.php');</script>";
  } 
  else {
    $redirect = "<script>window.location.assign('../index.php?err=1');</script>";
  }  

  echo str_replace("##redirect##", $redirect, file_get_contents('../login/page_only_title'));

?>
