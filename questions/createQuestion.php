<?php
  // Initialize session
  session_start();

  // Jump to login page if uid not set
  if (!isset($_SESSION['uid'])) {
    header('Location: ../');
    exit(0);
  }
  else if (time() > $_SESSION['expiry'])
  {
    session_unset();
    header('Location: ../login/login.php?TIMEOUT');
    exit(0);
  } else 
    $_SESSION['expiry'] = time() + 1800;
  

  $courseName = $_GET['courseName'];
  $date = $_GET['date'];

  if (in_array(array("type"=>"Student"),$_SESSION['type']))
    echo "<script>window.alert('You do not have permission to view.');
    window.location.assign('questionlist.php?courseName=$courseName&date=$date');</script>"; 
  else {
  if(isset($_GET['err']))
  {
    if($_GET['err'] == 1)
      $message =  "Question cannot consist of space only.";
    else if ($_GET['err'] == 2)
      $message =  "Answer cannot consist of space only.";
  }
  else
  $message = "<br />";

  $placeholder = array("##courseName##","##date##","##message##","##name##");
  $replace = array($courseName,$date,$message,$_SESSION['name']);
  echo str_replace($placeholder, $replace, file_get_contents('createQuestion'));
  }
?>
