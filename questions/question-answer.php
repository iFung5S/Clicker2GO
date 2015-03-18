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
      //use time() for current time directly ,in php unixtimestamp format
      // when the lecturer presses the start button starttime is set to current time on the server
      // otherwise is NULL
      $starttime = $question_row-> starttime; // in mySql string format
      // $countdown = $question_row-> countdown; // in seconds, not needed for now
      $endtime = $question_row-> endtime; // in mySql string format
  
      $cuid = $question_row->id_cu;
      $courseName = ORM::for_table('course_units')->find_one($cuid)->course;
      $date = $question_row->date;

      // count the number of answers. Or get it from database if
      // we decide to add it as a column(calculated at question creation)
      $num_answers = 0;
      for ($i = 1; $i <= 6; $i++)
        if (!is_null($question_row->get('answer'.$i)))
          $num_answers++;

      // the number of answers the student is required to select
      // for now calculate from number of correct answers -> not good for all types of question
      // better add column to database where the lecturer decides this
      // depending on the type of question
      $num_to_select = count(explode("|",$question_row-> correct));
      //don't need tell student
      //echo "Number of answers to select: $num_to_select";

      // vars required for generate_answers.php
      $show_given_answers = false;
      $show_correct_answers = false;
      $hidden_post_vars = "<input name='qid' type='hidden' value='$qid' />
                           <input name='seq' type='hidden' value='$seq' />
                           <input name='num_answers' type='hidden' value='$num_answers' />
                           <input name='num_to_select' type='hidden' value='$num_to_select' />";
      $refresh = "";
      if (is_null($starttime) || time() < strtotime($starttime))  // countdown has not been started yet by the lecturer
      {
        $info = "Question has not started yet.<br />" ;
        $refresh  = "<script src='//code.jquery.com/jquery-1.11.2.min.js'></script>
    <script type='text/javascript'>
      $(document).ready(function(){
        checkstart();
      });
      function checkstart() {
        $.post('check_start.php',{qid:$qid},function(data){
          if (data) {
            window.history.go(0);
          } else {
            setTimeout('checkstart()',5000);
          }
        });
      }
    </script>";
        $question = "";
        $form_action = "'record_answer.php'";
        $visible = false;
        $submit_button = "<div class='button-panel'><input class='btn_shadow_animate_grey_disabled' type='submit' value='Submit' disabled /></div>";
      }
      else // if countdown has started
      {

        $endtime = strtotime($endtime);

        // build the question string placeholders
        $question = $question_row-> question;

        $visible = true;

        // also if endtime has not arrived yet -> load the timer script
        if (time() < $endtime)
        {
          $form_action = "'record_answer.php'";

          // load the countdown timer
          $time_left = $endtime - time(); // until countdown ends
          // the action to be performed when countdown ends -> submit the form
          $timer_action = "document.getElementById('answer_form').submit()";

          // timer.php creates the $timer placeholder using $time_left and $timer_action as args
          // $timer also contains the timer javascript script
          include('timer.php');
          $info = $timer;
          $submit_button = "<div class='button-panel'><input class='button' type='submit' value='Submit' /></div>";
        }
        else // if currenttime > endttime, jump to answered page
        {
          $form_action = "'question-answered.php'";
          $uid = $_SESSION['uid'];
          //auto jump for student who has answered question
          $check_answered = ORM::for_table('answers')
                            ->where(array(
                              'qid'=>$qid,
                              'uid'=>$uid
                             ))
                            ->find_many();
          if (!empty($endtime) && time() > $endtime)
          {
            $submit_button="";
            $given_answer = array();
            foreach($check_answered as $single)
              array_push($given_answer,$single->answer);
            $submission_on_time = true;
            $show_given_answers = true;
            $visible = true;
            include('generate_answers.php');
            $answers = $answers."<script>document.getElementById('answer_form').submit()</script>";
            $placeholder = array("##info##", "##question##", "##answers##",
       "##courseName##","##date##","##date_long##","##qnumber##","##name##", "##refresh##","##qid##");
            $replace = array("", htmlspecialchars($question), $answers,$courseName, $date, date("d M Y",strtotime($date)), "", $_SESSION['name'], "", "");
            echo str_replace($placeholder, $replace, file_get_contents('question-answer'));
            exit(0);
          }
        }
      }

      // generate the answers_form html code
      // generate_answers.php stores the generated string code to $answers
      include('generate_answers.php');


      $placeholder = array("##info##", "##question##", "##answers##",
       "##courseName##","##date##","##date_long##","##qnumber##","##name##", "##refresh##","##qid##");
      $replace = array($info, htmlspecialchars($question), $answers,
                       $courseName, $date, date("d M Y",strtotime($date)), $seq, $_SESSION['name'], $refresh, $qid);
      echo str_replace($placeholder, $replace, file_get_contents('question-answer'));

    }
    else // if question_row is empty
    {
      $information = "Question ID '$qid' does not exist.";
      $placeholder = array("##information##","##name##", "##refresh##");
      $replace = array($information,$_SESSION['name'], $refresh);
      echo str_replace($placeholder, $replace, file_get_contents('error'));
    }
  }
  else // if $_GET['qid'] is not set
  {
    $information = "No question ID set. Please provide a question ID.";
    $placeholder = array("##information##","##name##", "##refresh##");
    $replace = array($information,$_SESSION['name'], $refresh);
    echo str_replace($placeholder, $replace, file_get_contents('error'));
  }
?>