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

  if(isset($_POST['qid']))
  {
    // the id of the answerd question
    $qid = $_POST['qid'];
    $seq = $_POST['seq'];

    // the current time is considered as the time that the answer was submitted
    $current_time = time();

    if(isset($_POST['answer']) || isset($_POST['no_answer']))
    {
      if(isset($_POST['answer']))
        $submitted_answer = $_POST['answer']; // this is an array

      // connect to mysql
      include_once ('../lib/dbCon.php');

      // get question data
      $question_row = ORM::for_table('questions')-> find_one($qid);

      if (!empty($question_row))
      {
        $cuid = $question_row->id_cu;
        $courseName = ORM::for_table('course_units')->find_one($cuid)->course;
        $date = $question_row->date;

        $question = $question_row-> question;

        $starttime = $question_row-> starttime; // in mySql string format
        if (is_null($starttime))
          //go back
          header("Location: question-answer.php?seq=$seq&qid=$qid");

        // define variables to pass to next page through post
        $num_answers = $_POST['num_answers']; // from POST or database
        $num_to_select = $_POST['num_to_select']; // from POST or database
        $hidden_post_vars = "<input name='qid' type='hidden' value='$qid' />
                             <input name='seq' type='hidden' value='$seq' />
                             <input name='num_answers' type='hidden' value='$num_answers' />
                             <input name='num_to_select' type='hidden' value='$num_to_select' />";
        $submit_button = '';

        // javascript to redirect to question-answered.php after 3 seconds timout
        // passing all the variables through post
        $redirection_script = "<script>
                               setTimeout(redirection, 3000);
                                 function redirection()
                                 {
                                   document.getElementById('answer_form').submit();
                                 }
                               </script>";


        // First confirm that submission was made in the time limits during the lecture

        // get starttime and endtime and convert from mysql to php format
        $starttime = strtotime($question_row->starttime);
        $endtime = strtotime($question_row->endtime);

        // now check the submission time
        // add two seconds to endtime to correct possible server latencies for lastsecond submissions
        $submission_on_time = ($current_time >= $starttime
                               && $current_time <= $endtime + 2);

        if ($submission_on_time)
        {
          // check if user has already answered the same question
          $check_repeat = ORM::for_table('answers')
                        ->where(array(
                               'qid'=>$qid,
                               'uid'=>$uid
                         ))
                        ->find_many();

          //if only 'no_answer' has been set
          if (isset($_POST['no_answer']) && !isset($_POST['answer']))
          {
            $info = "Time is up, you didn't choose any answer.";
            $submitted_answer = "";
          }
          else if(empty($check_repeat))
          {
            // record the answer only if user answers for the first time
            foreach($submitted_answer as $value)
            {
              $answer_row = ORM::for_table('answers')->create();
              $answer_row->qid = $qid;
              $answer_row->uid = $uid;
              $answer_row->answer = $value;
              $answer_row->save();      
            }
            $info = "Answer has been recorded.";
          }
          else
          {
            $info = "You have already answered on time for this question.
                    <br />
                    Recorded answer cannot be changed now.";
            // update the submitted_answers variable to the one previously recorded in the database
            $submitted_answer = array();
            foreach($check_repeat as $single)
              array_push($submitted_answer,$single->answer);

          }

          if ($current_time < $endtime)
          {
            $time_left = $endtime - $current_time; // until countdown ends
            // the action to be performed when countdown ends -> submit the above hidden form variables
            $timer_action = "document.getElementById('answer_form').submit()";

            // timer.php creates the $timer placeholder using $time_left and $action as args
            // $timer also contains the timer javascript script
            include('timer.php'); //timer as variable $timer

            // start the timer showing when the user is allowed to see the correct answer
            $info = $info . "<br/>Please wait " . $timer . " to see the correct answer(s)";

            // show the question and answers - (without the correct ones)



            // define the variables required by generate_answers.php
            $visible = true;
            $form_action = "'question-answered.php'";
            $show_given_answers = true;
            $given_answer = $submitted_answer;
            $show_correct_answers = false;

            include('generate_answers.php');

          }
          else // if it is submitted just in time or at countdown expiry
          {
            // don't start timer
            // don't show actual questions and answers but generate only the form
            $hidden_post_vars = $hidden_post_vars .
                                "<input name='num_to_select' type='hidden' value=$num_to_select />";


            // show message if the answer has been recorded or not and redirect after 5s timeout
            $info = $info .
                    "<br />
                     Please wait a moment. Redirecting to Answers ...
                     <br />";

            // define the variables required by generate_answers.php
            $visible = true;
            $form_action = "'question-answered.php'";
            $show_given_answers = true;
            $given_answer = $submitted_answer;
            $show_correct_answers = false;

            include('generate_answers.php');
            $answers = $answers . $redirection_script;
            // redirect (after a small timout to read the message)
            // to question-answered.php (if possible without javascript) - passing variables as POST
          }

        } // if $submission_on_time
        // else if submission was made after the deadline (endtime) show relevant message
        // This is only expected to happen if the previous questions page
        // was loaded without displaying the actual question and answers(only A. B. ..)
        // and the user had not reloaded after the lecturer started the countdown
        // but eventually submitted an answer, but after deadline.
        else if (current_time > $endtime + 2)
        {
          $info = "Submission after deadline.
                   <br />
                   Submitted answer will not be recorded and
                   not be taken into account for the statistics.
                   <br />
                   Please wait a moment. Redirecting to Answers ...
                   <br />" ;

          // don't record answer (is like the user has not attended the lecture)
          // don't start timer
          // don't show questions and answers

          // define the variables required by generate_answers.php
          $visible = true;
          $form_action = "'question-answered.php'";
          $show_given_answers = true;
          $given_answer = $submitted_answer;
          $show_correct_answers = false;

          include('generate_answers.php');
          $answers = $answers . $redirection_script;
        }

        $placeholder = array("##info##", "##question##", "##answers##",
        "##courseName##","##date##","##date_long##","##qnumber##","##name##", "##refresh##","##qid##");
        $replace = array($info, $question, $answers,
                       $courseName, $date, date("d M Y",strtotime($date)), $seq, $_SESSION['name'], "", $qid);
        echo str_replace($placeholder, $replace, file_get_contents('question-answer'));


      }
      else // if question_row is empty
      {
        $information = "The question with id '$qid' does not exist.";
        $placeholder = array("##information##","##name##");
        $replace = array($information,$_SESSION['name']);
        echo str_replace($placeholder, $replace, file_get_contents('error'));
      }


    }
    else // if $_POST['answer'] is not set
    {
      $information = "Sorry, you have not submitted an answer.";
      $placeholder = array("##information##","##name##");
      $replace = array($information,$_SESSION['name']);
      echo str_replace($placeholder, $replace, file_get_contents('error'));
    }


  }
  else // if $_POST['qid'] is not set
  {
    $information = "The question id has not been provided.";
    $placeholder = array("##information##","##name##");
    $replace = array($information,$_SESSION['name']);
    echo str_replace($placeholder, $replace, file_get_contents('error'));
  }


?>
