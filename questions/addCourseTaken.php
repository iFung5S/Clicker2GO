<!DOCTYPE HTML>
<?php
// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: ../login/login.php');
}
  $username = $_SESSION['username'];
  include_once ('../dbCon.php');
?>
<html> 
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Adding | Clicker2GO</title>
    <link rel="stylesheet" href="./style.css">
  </head>
  <body class="homepage">

  <?php

    $courseName = $_POST['courseName'];
  if (!empty($courseName)||preg_match("/^[a-zA-Z0-9]*$/",$courseName) {
    $user = ORM::for_table('user')
          ->find_one($username);
    $course = $user->course;
    if (empty($course)) {
      $course = $courseName; }
    else {
      $course = implode("|",array($course,$courseName)); }
    $user->course = $course;
    $user->save();
    echo "<script>window.location.assign('../index.php');</script>";
  } 
  else {
    echo "<script>window.location.assign('../index.php');</script>";
  }
   
?>

</body>
</html>