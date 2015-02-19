<?php
  // Initialize session
  session_start();

  // Jump to login page if uid not set
  if (!isset($_SESSION['uid'])) {
        header('Location: ../');
  }
  $courseName = $_POST['courseName'];
  $date = $_POST['date'];
  $question = str_replace("\n","<br/>",$_POST['question']);

  for($i = 1;$i <= 6;$i++){
    $answer[$i] = $_POST['answer'.$i];
  }
  if(isset($_POST['correct'])){
    $correct = $_POST['correct'];
    $correct = implode("|",$correct);
  }

  include_once ('../lib/dbCon.php');

  $cuid = ORM::for_table('course_units')
          ->where('course',$courseName)
          ->find_one()->id;
  $questions = ORM::for_table('questions')
                ->create();
  $questions -> set(array(
                 'id_cu' => $cuid, 
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

  $redirect = "<script>window.location.assign('questionlist.php?courseName=$courseName&date=$date');</script>";

  echo str_replace("##redirect##", $redirect, file_get_contents('../login/page_only_title'));

?>

