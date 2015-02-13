<?php


  // Initialize session
  session_start();

  // Jump to login page if username not set
  if (!isset($_SESSION['username'])) {
          header('Location: ../login/login.php');
  }

  // the id of the question to be displayed.
  $qid = $_GET['qid'];
  // $qid = 1;  // Hard coded value. uncomment for testing
  // $qid = $_POST['qid'];


  // connect to mysql
  include_once ('../lib/dbCon.php');

  // get question data
  $question_row = ORM::for_table('questions')-> find_one($qid);

  $currenttime = time();
  // when the lecturer presses the start button starttime is set to current time on the server
  // otherwise is NULL
  $starttime = $question_row-> starttime;
  $endtime = $question_row-> endtime;
  $count_started = !is_null($starttime);  // ?should we compare with current time also?
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
                    <input name='answer' type='radio' value=$i /> $N. $answer
                  </li>";
    }
  }


  // load the Countdown timer script
  $timer_script = "";
  if ($count_started && $currenttime < $endtime)
  {
    $timer_script = "<script type='text/javascript'>
        var count = $countdown;
        var counter = setInterval(timer, 1000);
        var message = '';
        function timer()
        {
          count -= 1;
          if (count < 0)
          {
          clearInterval(counter);
          message = 'Ended.';
          document.getElementById('timer').innerHTML = message;
          document.getElementById('answer_form').submit();
          }
          else
          {
            var seconds = count % 60;
            var minutes = Math.floor(count / 60);
            var hours = Math.floor(minutes / 60);
            minutes %= 60;
            hours %= 60;
            if (seconds < 10) // Add leading zero if seconds is 1 digit
              seconds = '0' + seconds;
            if (minutes < 10) // Add leading zero if minutes is 1 digit
              minutes = '0' + minutes;
            message = minutes + ':' + seconds;
            if (hours > 0)
            {
              if (hours < 10) // Add leading zero if hours is 1 digit but not 0
                hours = '0' + hours;
              message = hours + ':' + message; // Print hours only if > 0
            }
          }
        document.getElementById('timer').innerHTML = message;
        }
      </script>";
  }


  $placeholder = array("##reload##", "##question##", "##answers##", "##timer_script##");
  $replace = array($reload, $question, $answers, $timer_script);
  echo str_replace($placeholder, $replace, file_get_contents('question-answer'));
  
  
      

?>
