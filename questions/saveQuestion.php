<?php
  // Initialize session
  session_start();

  // Jump to login page if uid not set
  if (!isset($_SESSION['uid']))
  {
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

  if (in_array(array("type"=>"Student"),$_SESSION['type']))
  {
    echo "<script>window.alert('You do not have permission for this operation.');
    window.location.assign('../');</script>";
  exit(0);
  }
  else {
  $courseName = $_POST['courseName'];
  $date = $_POST['date'];

  if (preg_match("/^\s*$/",$_POST['question']))
  {
    $redirect = "<script>window.location.assign('createQuestion.php?courseName=$courseName&date=$date&err=1');</script>";
  }

    for($i = 1;$i <= 6;$i++){

      if(strlen($_POST['answer'.$i])!= 0
         && preg_match("/^\s*$/",$_POST['answer'.$i]))
      {
        $redirect = "<script>window.location.assign('createQuestion.php?courseName=$courseName&date=$date&err=2');</script>";
      }
      $answer[$i] = $_POST['answer'.$i];
    }
    if (empty($redirect)){

    if(isset($_POST['correct'])){
      $correct = $_POST['correct'];
      $correct = implode("|",$correct);
    }
    else
      $correct = null;
    include_once ('../lib/dbCon.php');

    $cuid = ORM::for_table('course_units')
            ->where('course',$courseName)
            ->find_one()->id;
    $questions = ORM::for_table('questions')
                 ->create();
    $questions -> set(array(
                  'id_cu' => $cuid,
                  'date' => $date,
                  'question' => $_POST['question'],
                  'correct' => $correct
                 ));
    for ($i=1; $i<=6; $i++)
    {
      // not using !empty() because if we have an answer = 0 it is not accepted
      if (!empty($answer[$i]))
        $questions->set('answer'.$i,$answer[$i]);
    }
    $questions -> save();

    $redirect = "<script>window.location.assign('questionlist.php?courseName=$courseName&date=$date');</script>";
    }
  }
  echo str_replace("##redirect##", $redirect, file_get_contents('../login/page_only_title'));
  
?>