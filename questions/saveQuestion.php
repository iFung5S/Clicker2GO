<!DOCTYPE html>
<?php
// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: ../login/login.php');
}
  $courseName = $_POST['courseName'];
  $date = $_POST['date'];
  $question = $_POST['question'];
  for($i = 1;$i <= 6;$i++){
    $answer[$i] = $_POST['answer'.$i];
  }
  if(isset($_POST['correct'])){
    $correct = $_POST['correct'];
    $correct = implode("|",$correct);
  }

  include_once ('../lib/dbCon.php');
?>
<html>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Creating | Clicker2GO</title>
    <link rel="stylesheet" href="../style.css">
  </head>

  <body class="homepage">
  <?php

    $questions = ORM::for_table('questions')
                 ->create();
    $questions -> set(array(
                  'course' => $courseName, 
                  'date' => $date, 
                  'question' => $question, 
                  'answer1' => $answer[1], 
                  'answer2' => $answer[2], 
                  'answer3' => $answer[3], 
                  'answer4' => $answer[4], 
                  'answer5' => $answer[5], 
                  'answer6' => $answer[6], 
                  'correct' => $correct
                  ));
    $questions -> save();

    echo "<script>window.location.assign('questionlist.php?courseName=$courseName&date=$date');</script>";

  ?>
  </body>
</html>
