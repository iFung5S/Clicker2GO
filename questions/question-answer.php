<?php

  // Initialize session
  session_start();

  // Jump to login page if uid not set
  if (!isset($_SESSION['uid']))
    header('Location: ../');

  if(isset($_GET['qid']))
  {
    // the id of the question to be displayed.
    $qid = $_GET['qid'];
    $seq = $_GET['seq'];
    // connect to mysql
    include_once ('../lib/dbCon.php');

    // get question data
    $question_row = ORM::for_table('questions')-> find_one($qid);

    // check for errors
    if (!empty($question_row))
    {
      $currenttime = time(); // in php unixtimestamp format
      // when the lecturer presses the start button starttime is set to current time on the server
      // otherwise is NULL
      $starttime = $question_row-> starttime; // in mySql string format
      // $countdown = $question_row-> countdown; // in seconds, not needed for now
      $endtime = $question_row-> endtime; // in mySql string format

      /*
      //auto jump for student who has answered question
      // [Paris] -> not needed because we still want the student to be able
      // to try again answering the question but without recording the result to database
      $uid = $_SESSION['uid'];
      $check_answered = ORM::for_table('answers')
                      ->where(array(
                             'qid'=>$qid,
                             'uid'=>$uid
                       ))
                      ->find_one();
      if (!empty($check_answered)||!empty($endtime) && $currenttime > $endtime)
      {
        $answers = "<input name='qid' type='hidden' value='$qid' />
                    <input name='seq' type='hidden' value='$seq' />
                    <input name='answer' type='hidden' value='' />
                    <input type='submit' value='Submit' />
                    <script>document.getElementById('answer_form').submit()</script>";
        $placeholder = array("##reload##", "##question##", "##answers##"
          ,"##timer_script##","##courseName##","##date##","##qnumber##","##name##");
        $replace = array("", "", $answers, ""
                       ,$courseName,$date,$seq,"");
        echo str_replace($placeholder, $replace, file_get_contents('question-answer'));
      }
      else {
      */

      $cuid = $question_row->id_cu;
      $courseName = ORM::for_table('course_units')->find_one($cuid)->course;
      $date = $question_row->date;

      // count the number of answers. Or get it from database if
      // we decide to add it as a column(calculated at question creation)
      $num_answers = 0;
      for ($i=1; $i<=6; $i++)
        if (!is_null($question_row->get('answer'.$i)))
          $num_answers++;

      // the number of answers the student is required to select
      // for now calculate from number of correct answers -> not good for all types of question
      // better add column to database where the lecturer decides this
      // depending on the type of question
      $num_to_select = count(explode("|",$question_row-> correct));
      echo "Number of answers to select: $num_to_select";

      // vars required for generate_answers.php
      $show_given_answers = false;
      $show_correct_answers = false;
      $hidden_post_vars = "<input name='qid' type='hidden' value='$qid' />
                           <input name='seq' type='hidden' value='$seq' />
                           <input name='num_answers' type='hidden' value='$num_answers' />
                           <input name='num_to_select' type='hidden' value='$num_to_select' />";
      $submit_button = "<div class='button-panel'><input class='button' type='submit' value='Submit'></div>";


      if (is_null($starttime))  // countdown has not been started yet by the lecturer
      {
        // display a Reload Question button so the user can reload the page when
        // told by the lecturer that countdown has started
        $reload_button = "<input class='btn_shadow_animate_green' type='button' value='Reload Question' onClick='history.go(0)'>";
        $info = "Please reload when the lecturer starts the timer.
                <br />" .
                $reload_button;
        $question = "";
        $form_action = 'record_answer.php';
        $visible = false;
      }
      else // if countdown has started
      {
        // transform from mysql time string to php unix timestamp
        // $starttime = strtotime($starttime); // for startime not needed as not used afterwards
        $endtime = strtotime($endtime);

        // build the question string placeholders
        $question = $question_row-> question;

        $visible = true;

        // also if endtime has not arrived yet -> load the timer script
        if ($currenttime < $endtime)
        {
          $form_action = 'record_answer.php';

          // load the countdown timer
          $time_left = $endtime - $currenttime; // until countdown ends
          // the action to be performed when countdown ends -> submit the form
          $timer_action = "document.getElementById('answer_form').submit()";
          // $answers = $answers."<input name='answer' type='hidden' value='' />"; // ????

          // timer.php creates the $timer placeholder using $time_left and $timer_action as args
          // $timer also contains the timer javascript script
          include('timer.php');
          $info = $timer;

        }
        else // if currenttime > endttime, i.e revisiting already answered questions
        {
          // go directly to answered questions. No need to try to record the answer
          $form_action = 'question-answered.php';
          $info = "Browse and simulate answers - No record";
          $submit_button = "<div class='button-panel'><input class='button' type='submit' value='Show Answers' /></div>";
        }

      }

      // generate the answers_form html code
      // generate_answers.php stores the generated string code to $answers
      include('generate_answers.php');


      $placeholder = array("##info##", "##question##", "##answers##",
                        "##courseName##","##date##","##qnumber##","##name##");
      $replace = array($info, $question, $answers,
                       $courseName, $date, $seq, $_SESSION['name']);
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
  else // if $_GET['qid'] is not set
  {
    $information = "The question id is empty. Please provide a question id (?qid=)";
    $placeholder = array("##information##","##name##");
    $replace = array($information,$_SESSION['name']);
    echo str_replace($placeholder, $replace, file_get_contents('error'));
  }
?>
