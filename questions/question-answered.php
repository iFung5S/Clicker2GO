<?php


  // Initialize session
  session_start();

  // Jump to login page if username not set
  if (!isset($_SESSION['username']))
  {
          header('Location: ../login/login.php');
  }
  else
  {
    $username = $_SESSION['username'];
  }

  // get the time the answer was submitted
  $submission_time = time();


  // get the answer submitted (this is string 1,2,3...)
  $submitted_answer = "answer" . $_POST['answer'];

  // the id of the answerd question
  $qid = $_POST['qid'];

  // connect to mysql
  include_once ('../lib/dbCon.php');

  // get question data
  $question_row = ORM::for_table('questions')-> find_one($qid);

  // check if submission was in the time limits
  // get the starttime and convert from mysql to php format
  $starttime = strtotime($question_row->starttime);

  // $countdown = 30; // hardcoded for now until i change database structure
  $countdown = $question_row->countdown; // in seconds

  // calculate end time
  //tmp
  //$endtime = strtotime($question_row->endtime);
  $endtime = $starttime + $countdown;

 // $username = "test"; // hardcoded for now

  if ($submission_time >= $starttime && $submission_time <= $endtime)
  {
    //check if user have already answered same question
    $check_repeat = ORM::for_table('answers')
                    ->where(array(
                           'qid'=>$qid,
                           'username'=>$username
                     ))
                    ->find_one();
    if(empty($check_repeat))
    {
      // record the answer
      $answer = ORM::for_table('answers')->create();
      $answer->set(array(
                   'qid'=>$qid,
                   'username'=>$username,
                   'answer'=>$submitted_answer
                ));
      $answer->save();      // to correct
    echo "Success";
    }
    else
    {
      echo "You have already answered this question";
    }
  }
  else
  {
     echo "Sorry, overtime now";  //print message to know what happened
  }
    // users should have a unique id int to call instead of user name


  // to display if question is right or wrong
  // tokenize correct answers
  //$result_row('correct');

  // build the answers string
  if ($submitted_answer == $question_row-> correct )
  {
    $answers = " You are correct!!<br>";
  }
  else
    $answers = " You are wrong.<br>";


    $a="ABCDEF";
    // use index i for the answer number
    for ($i=1; $i<=6; $i++) // 6 are the maximum allowed answers
    {
      $answer = $question_row->get('answer'.$i); // get the specific answer
      if (!empty($answer)) // only print the answer if it exists (its value not NULL)
      {
        //if
        //$isCorrect = "
        $N=$a[$i-1];
        $answers = $answers .
                    "<li>
                      $N. $answer
                    </li>";





  }


  $placeholder = array("##question##","##answers##",);
  $replace = array($question,$answers);
  echo str_replace($placeholder, $replace, file_get_contents('question-answered'));


?>
