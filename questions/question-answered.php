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
  }

if(isset($_POST['qid'])){
  // the id of the answerd question
  $qid = $_POST['qid'];
  $seq = $_POST['seq'];
  // get the time the answer was submitted
  $currenttime = time();
  $submission_time = $currenttime ;

 if(isset($_POST['answer'])){
  // get the answer submitted (this is string 1,2,3...)
  $answer = $_POST['answer'];
  $submitted_answer = "answer" . $answer;

  // connect to mysql
  include_once ('../lib/dbCon.php');

  // get question data
  $question_row = ORM::for_table('questions')-> find_one($qid);
  $cuid = $question_row->id_cu;
  $courseName = ORM::for_table('course_units')->find_one($cuid)->course;
  $date = $question_row->date;

 if (!empty($question_row)) {

  // check if submission was in the time limits
  // get the starttime and convert from mysql to php format
  $starttime = strtotime($question_row->starttime);

  // $countdown = 30; // hardcoded for now until i change database structure
  // $countdown = $question_row->countdown; // in seconds

  // calculate end time
  //$endtime = $starttime + $countdown; 
  $endtime = strtotime($question_row->endtime);

  // seconds left
  $seconds_left = $endtime - $submission_time;

  // a variable to hold the ##reload## placeholder
  $reload = "";
  
  // check if the submission is made within the defined time limits
  $submission_on_time = ($submission_time >= $starttime 
                         && $submission_time <= $endtime);
  $check_answer = true;
  $graph = "";

  //check if user has already answered the same question
  $check_repeat = ORM::for_table('answers')
                  ->where(array(
                         'qid'=>$qid,
                         'uid'=>$uid
                   ))
                  ->find_one();
  if ($submission_on_time)
  {
    
    if(empty($check_repeat))
    {
      // record the answer only if user answers for the first time
      $answer_row = ORM::for_table('answers')->create();
      $answer_row->set(array(
                   'qid'=>$qid,
                   'uid'=>$uid,
                   'answer'=>$submitted_answer
                ));
      $answer_row->save();      // to correct
      $reload = "Your answer has been recorded";
    }
    else
    {
      $reload = "You have already submitted an answer for this question";
    }
    
    // add a hidden form varible to pass to next page after re-submission when timer ends
    $answers = "<input name='qid' type='hidden' value=$qid />
                <input name='answer' type='hidden' value=$answer />
                <input name='submission_on_time' type='hidden' value=$submission_on_time />";
    
    $action = "document.getElementById('answer_form').submit()";
    include('timer.php'); //timer as variable $timer

    // start a timer showing when the user is allowed to see the correct answer
    $reload = $reload . "<br/>Please wait".$timer." to see the answer";
    
  }
  else if (!empty($check_repeat))
  {
    $reload = "You have answered this question";
    $submitted_answer = $check_repeat->answer;
  }
  // if submission is after the deadline (endtime) show message
  else if (!isset($_POST['submission_on_time']))
  {
     $reload = "Sorry, Question has closed." ;
     $check_answer = false;
  }
  
  
  // Display the results only if server's end time has passed
  // take the current(last) submission as valid -> do not check database for previous submitions
  // do not make changes to the database (only the original submission is stored)   
   $question = "";//initialize
  if ($submission_time > $endtime)
  {
    // build the question string
    $question = $question_row-> question;
    
    // build the answers string
    $correct_answer = explode("|",$question_row-> correct);
    // convert correct answer to int (take only the last string digit with -1)

    $answers = "<p style='font-size:14px;color:grey;font-style:italic'>Your answer is in bold</p>";

    $numbering_characters="ABCDEF";
    
    // use a loop to generate html form radio buttons(grayed out) with the answers
    // indicate the given answer and the correct answer
    // use index i for the answer number
    for ($i=1; $i<=6; $i++) // 6 are the maximum allowed answers
    {
      
      $answer = $question_row->get('answer'.$i); // get the specific answer
      if (!empty($answer)) // only print the answer if it exists (its value not NULL)
      {
        $isCorrect = "";
        if (in_array('answer'.$i, $correct_answer))
          $isCorrect = "<span style='font-size:12px;color:green;'>  (Correct)</span>";  // we nust decide how to show correct answer
        $N=$numbering_characters[$i-1];
        if ('answer'.$i == $submitted_answer)
          $answers = $answers ."<li style='font-weight:bold;'>";
        else
          $answers = $answers ."<li>";
        $answers = $answers .
                    "$N. $answer  $isCorrect
                    </li>";
      }
    }
    include('graph.php');
  }


  $comment = "<iframe src='comment.php?qid=$qid'></iframe>";

  $placeholder = array("##reload##", "##question##", "##answers##","##graph##"
              ,"##comment##","##courseName##","##date##","##qnumber##","##name##");
  $replace = array($reload, $question, $answers,$graph,$comment,
                    $courseName,$date,$seq,$_SESSION['name']);
  echo str_replace($placeholder, $replace, file_get_contents('question-answered'));

//for errors
 }
 else {
  $information = "The question with id '$qid' is not exist.";
  echo str_replace("##information##", $information, file_get_contents('error'));
 }
}
else {
  $information = "Sorry, you haven't submit answer.";
  echo str_replace("##information##", $information, file_get_contents('error'));
 }
}
else {
  $information = "The question id is empty.";
  echo str_replace("##information##", $information, file_get_contents('error'));
} 

?>
