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
  if ($submission_on_time)
  {
    
    //check if user has already answered the same question
    $check_repeat = ORM::for_table('answers')
                    ->where(array(
                           'qid'=>$qid,
                           'username'=>$username
                     ))
                    ->find_one();
    if(empty($check_repeat))
    {
      // record the answer only if user answers for the first time
      $answer = ORM::for_table('answers')->create();
      $answer->set(array(
                   'qid'=>$qid,
                   'username'=>$username,
                   'answer'=>$submitted_answer
                ));
      $answer->save();      // to correct
      $reload = "Your answer has been recorded";
    }
    else
    {
      $reload = "You have already submitted an answer for this question";
    }
    
    // add a hidden form varible to pass to next page after re-submission when timer ends
    $answers = "<input name='qid' type='hidden' value=$qid />
                <input name='submission_on_time' type='hidden' value=$submission_on_time />";
    
    // start a timer showing when the user is allowed to see the correct answer
    $reload = $reload . "Please wait <span id='timer'></span> seconds to see the answer";
    
  }
  // if submission is after the deadline (endtime) show message
  else if (!isset($_POST['submission_on_time']))
  {
     $reload = "Sorry, You have missed the deadline. <br>
                Your answer is not taken into account in the statistics." ;
  }
  
  
  // Display the results only if server's end time has passed
  // take the current(last) submission as valid -> do not check database for previous submitions
  // do not make changes to the database (only the original submission is stored)   
  if ($submission_time > $endtime)
  {
    // build the question string
    $question = $question_row-> question;
    
    // build the answers string
    $correct_answer = $question_row-> correct;
    // convert correct answer to int (take only the last string digit with -1)
    $correct_answer_int = (int) substr($correct_answer, -1);
    
    if ($submitted_answer == $correct_answer )
    {
      $answers =" You are correct!!<br>";
    }
    else
      $answers =" You are wrong.<br>";


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
        if ('answer'.$i == $correct_answer)
          $isCorrect = "Correct Answer";  // we nust decide how to show correct answer
        $N=$numbering_characters[$i-1];
        
        $answers = $answers .
                    "<li>
                      <input name='answer' type='radio' value=$correct_answer_int disabled />$N. $answer  $isCorrect
                    </li>";
      }
    }
  }


  // load the Countdown timer script
  $timer_script = "";
  if ($submission_on_time)
  {
    $timer_script = "<script type='text/javascript'>
        var count = $seconds_left;
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
  echo str_replace($placeholder, $replace, file_get_contents('question-answered'));
  

?>
