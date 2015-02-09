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
  include_once ('../lib/sqlconnect.php');

  // get question data
  $result = mysqli_query($conn, "SELECT * FROM `questions` WHERE id=$qid");
  $result_row = mysqli_fetch_assoc($result);

  // check if submission was in the time limits
  // get the starttime and convert from mysql to php format
  $starttime = strtotime($result_row["starttime"]);

  // $countdown = 30; // hardcoded for now until i change database structure
  $countdown = $result_row["countdown"]; // in seconds

  // calculate end time
  $endtime = strtotime($result_row["endtime"]);

 // $username = "test"; // hardcoded for now

  if ($submission_time >= $starttime && $submission_time <= $endtime)
  {
    // record the answer
    $query = "INSERT INTO `answers` (`qid`, `username`, `answer`) VALUES ('$qid', '$username', '$submitted_answer');";
    $record_answer = mysqli_query($conn, $query); // to correct
    echo "Success";
  }
    // users should have a unique id int to call instead of user name

/*
  // to display if question is right or wrong
  // tokenize correct answers
  $result_row('correct');

  if ($submitted_answer ==
    echo right
  else
    echo wrong..

  $question = $result_row['question'];



  // build the answers string
  $answers = "";
  // use index i for the answer number
  for ($i=1; $i<=6; $i++) // 6 are the maximum allowed answers
  {
    $answer=$result_row['answer' . $i]; // get the specific answer
    if (!empty($answer)) // only print the answer if it exists (its value not NULL)
    {
      // if start button not pressed by lecturer do not show the actual questions
      // instead only print the A, B, C... placeholders
      if (!$countStarted)
        $answer = " ";

      $answers = $answers .
                  "<li>
                    <input name='answer' type='radio' value=$i /> $answer
                  </li>";
    }
  }


  $placeholder = array("##question##","##answers##",);
  $replace = array($question,$answers);
  echo str_replace($placeholder, $replace, file_get_contents('question-answer'));

*/
?>
