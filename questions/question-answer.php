<?php


  // Initialize session
  session_start();

  // Jump to login page if username not set
  if (!isset($_SESSION['username'])) {
          header('Location: ../');
  }

  // the id of the question to be displayed.
  $qid = $_GET['qid'];
  // $qid = 1;  // Hard coded value. uncomment for testing
  // $qid = $_POST['qid'];


  // connect to mysql
  include_once ('../lib/dbCon.php');

  // get question data
  $question_row = ORM::for_table('questions')-> find_one($qid);
  $courseName = $question_row->course;
  $date = $question_row->date;
  $currenttime = time();
  // when the lecturer presses the start button starttime is set to current time on the server
  // otherwise is NULL
  $starttime = $question_row-> starttime;
  $endtime = strtotime($question_row-> endtime);
  $count_started = !is_null($starttime);  // ?should we compare with current time also?
  $starttime = strtotime($starttime);
  //$count_started = (int) $_GET['started']; // for testing
  //$countdown = 30; // hardcoded for now until i change database structure
  $countdown = $question_row-> countdown; // in seconds

  // build the timer script -> not needed probably
  // $timer = "";
  
  // build the reload and question string placeholders
  if ($count_started)
  {
    $reload = "";
    $question = $question_row-> question;
  }
  else
  {
    // for now use qid. To implement:it should return the question number within the lecture
    // $reload = header("Location: /$_SERVER['PHP_SELF']");
    // $page = $_SERVER['PHP_SELF'];
    $reload = "<input type='button' VALUE='Reload Question' onClick='history.go(0)'>";
    $question = "Question $qid";
  }

  // build the answers string
  
  $numbering_characters="ABCDEF";
  
  // add a hidden form varible to pass the qid to next page after submition
  $answers = "<input name='qid' type='hidden' value=$qid />";
  
  // use a loop to generate html form radio buttons with the answers
  // use index i for the answer number
  for ($i=1; $i<=6; $i++) // 6 are the maximum allowed answers
  {
    $answer = $question_row->get('answer'.$i); // get the specific answer
    if (!empty($answer)) // only print the answer if it exists (its value not NULL)
    {
      // if start button not pressed by lecturer do not show the actual questions
      // instead only print the A, B, C... placeholders
      if (!$count_started)
        $answer = "";

      $N=$numbering_characters[$i-1];
      $answers = $answers .
                  "<li>
                    <input name='answer' type='radio' value=$i id=$N required>
                    <label for=$N> $N. $answer</label></br>
                  </li>";
    }
  }
  $answers = $answers."</ol>";

  // load the Countdown timer script

  if ($count_started && $currenttime < $endtime)
  {
    $action = "document.getElementById('answer_form').submit()";
    $answers = $answers."<input name='answer' type='hidden' value='' />";
    include('timer.php'); //timer as variable $timer
  }
  else
    $timer= "";

  $answers = $answers."<input type='submit' value='Submit' ";

  if(!$count_started)
  {
   $answers = $answers."disabled>";
  }
  else
  {
    $answers = $answers.">";
  }

  $placeholder = array("##reload##", "##question##", "##answers##",
                       "##timer_script##","##courseName##","##date##");
  $replace = array($reload, $question, $answers, $timer,$courseName,$date);
  echo str_replace($placeholder, $replace, file_get_contents('question-answer'));
  

?>
