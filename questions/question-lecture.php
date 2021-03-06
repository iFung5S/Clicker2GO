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

  $uid = $_SESSION['uid'];
  // connect to mysql
  include_once ('../lib/dbCon.php');


  if(isset($_GET['qid']))
  {

    if (isset($_GET['seq']))
      $seq = $_GET['seq'];
    else
      $seq = "";

    $qid = $_GET['qid'];
      // check if user is a lecturer
    if (!in_array(array("type"=>"Lecturer"),$_SESSION['type']))
      header("Location: question-answer.php?seq=$seq&qid=$qid");

    $question_row = ORM::for_table('questions')-> find_one($qid);

    if (!empty($question_row)) {

      $cuid = $question_row->id_cu;
      $courseName = ORM::for_table('course_units')->find_one($cuid)->course;
      $date = $question_row->date;

      $correct_answer = explode("|",$question_row-> correct);
      $question = $question_row-> question;
      $numbering_characters="ABCDEF";
      $answers = "";

      for ($i = 1; $i <= 6; $i++)
      {

        $answer = $question_row->get('answer'.$i);
        if (!is_null($answer))
        {
          $isCorrect = "";
          if (in_array('answer'.$i, $correct_answer))
            $isCorrect = "id='answer$i'";
          $N=$numbering_characters[$i-1];

          $answers .= "<div $isCorrect style='margin-bottom:0.5em'>
                      <span style='float:left'>$N. </span>
                      <div style='margin-left:1.8em'>".htmlspecialchars($answer)."</div></div>";
        }
      }

  if ($question_row->starttime != NULL)
  {
    if (strtotime($question_row->endtime) <= time())
      $time_left = -1;
    else
      $time_left = strtotime($question_row->endtime) - time();
    $timer_action = "";
    foreach ($correct_answer as $id)
      $timer_action .= "document.getElementById('$id').style.color='green';";
    include('timer.php');
  }
  else
    $timer ="has not started yet.";
  $default = $question_row->countdown;

    $question = str_replace("\n","<br/>",htmlspecialchars($question));
    $placeholder = array("##question##", "##answers##","##timer##",
      "##default##","##qid##","##courseName##","##date##","##qnumber##","##name##");
    $replace = array($question, $answers,$timer,$default
                ,$qid,$courseName,$date,$seq,$_SESSION['name']);
    echo str_replace($placeholder, $replace, file_get_contents('question-lecture'));

  //for errors
   }
   else {
    $information = "The question with id '$qid' is not exist.";
    $placeholder = array("##information##","##name##");
    $replace = array($information,$_SESSION['name']);
    echo str_replace($placeholder, $replace, file_get_contents('error'));
   }
  }
  else {
    $information = "The question id is empty.";
    $placeholder = array("##information##","##name##");
    $replace = array($information,$_SESSION['name']);
    echo str_replace($placeholder, $replace, file_get_contents('error'));
  }
?>
