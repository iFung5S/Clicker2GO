<?php
  // Initialize session
  session_start();

  // Jump to login page if uid not set
  if (!isset($_SESSION['uid'])) {
        header('Location: ../');
  }

  $courseName = $_GET['courseName'];
  $date = $_GET['date'];

  if(isset($_GET['err']))
  {
    if($_GET['err'] == 1)
      $message =  "Question cannot consist of space only.";
    else if ($_GET['err'] == 2)
      $message =  "Answer cannot consist of space only.";
  }
  else
  $message = "<br/>";

  $placeholder = array("##courseName##","##date##","##message##","##name##");
  $replace = array($courseName,$date,$message,$_SESSION['name']);
  echo str_replace($placeholder, $replace, file_get_contents('createQuestion'));
?>

