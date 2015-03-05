<?php


  // Initialize session
  session_start();

  // Jump to login page if uid not set
  if (!isset($_SESSION['uid']))
  {
          header('Location: ../');
  }
  else
  {
    $uid = $_SESSION['uid'];
    // connect to mysql
    include_once ('../lib/dbCon.php');
    // check if user is a lecturer (user type = 2)
    $is_lecturer = ORM::for_table('u_type')
                        ->where(array(
                               'uid'=>$uid,
                               'id_t'=>2
                         ))
                        ->find_one();
    // check if he is the onwer of the question(when we make changes to database)
    // $is_owner
  }

  if(isset($_GET['qid']))
  {

    if (isset($_GET['seq']))
      $seq = $_GET['seq'];
    else
      $seq = "";

    $qid = $_GET['qid'];
    if (empty($is_lecturer))
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

      for ($i=1; $i<=6; $i++)
      {

        $answer = $question_row->get('answer'.$i);
        if (!empty($answer))
        {
          $isCorrect = "";
          if (in_array('answer'.$i, $correct_answer))
            $isCorrect = "name='correct'";
          $N=$numbering_characters[$i-1];

          $answers = $answers .
                      "<div $isCorrect style='margin-bottom:0.5em'>
                      <span style='float:left'>$N. </span>
                      <div style='margin-left:1.8em'>$answer</div></div>";
        }
      }


  if ($question_row->starttime != NULL)
  {
    if (strtotime($question_row->endtime) <= time())
      $time_left = -1; 
    else
      $time_left = strtotime($question_row->endtime) - time(); 
    $timer_action = "var correct = document.getElementsByName('correct');
                     for(var i=0;i<correct.length;i++){
                       correct[i].style.color='green'; }";
    include('timer.php');
  }
  else
    $timer ="not start"; 
  $default = $question_row->countdown;
    

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
