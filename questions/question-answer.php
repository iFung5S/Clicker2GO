<?php


  // Initialize session
  session_start();

  // Jump to login page if username not set
  if (!isset($_SESSION['username'])) {
          header('Location: ../login/login.php');
  }

  // the id of the question to be displayed.
  $qid = $_GET['qid'];
  //$qid = 1;  // Hard coded value. uncomment for testing


  // connect to mysql
  include_once ('../lib/sqlconnect.php');

  // get question data
  $result = mysqli_query($conn, "SELECT * FROM `questions` WHERE id=$qid");
  $result_row = mysqli_fetch_assoc($result);

  // when the lecturer presses the start button starttime is set to current time on the server
  // otherwise is NULL
  $starttime = $result_row["starttime"];
  $countStarted = !($starttime == NULL);  // ?should we compare with current time also?
  //$countStarted = (int) $_GET['started']; // for testing
  $countdown = 30; // hardcoded for now until i change database structure
  //$countdown = $result_row["countdown"]; // in seconds


  // build the question string
  if ($countStarted)
    $question = $result_row['question'];
  else
  {
    // for now use qid. To implement:it should return the question number within the lecture
    //$reload = header("Location: /$_SERVER['PHP_SELF']");
    //$page = $_SERVER['PHP_SELF'];
    $question = "Question $qid" .
                "<br>
                <input type='button' VALUE='Reload Question' onClick='history.go(0)'>";
  }


  // build the answers string
  $answers = "<input name='qid' type='hidden' value=$qid />";
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


?>
