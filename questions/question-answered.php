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
    // get the current time
    $current_time = time();

    // connect to mysql
    include_once ('../lib/dbCon.php');

    // get question data
    $question_row = ORM::for_table('questions')-> find_one($qid);

    if (!empty($question_row))
    {
      $cuid = $question_row->id_cu;
      $courseName = ORM::for_table('course_units')->find_one($cuid)->course;
      $date = $question_row->date;

      if(isset($_POST['answer']))
        // get the answer submitted This is expected to be an array
        $submitted_answer = $_POST['answer'];
      else
        $submitted_answer = array(); // empty array

      $endtime = strtotime($question_row->endtime);


      // Display the correct answers only if server's end time has passed
      // take the current(last) submission, passed from previous page through POST,
      // as valid -> do not check database for previous submissions
      // do not make changes to the database
      if ($current_time > $endtime)
      {

        // build the question string
        $question = $question_row-> question;

        // build the answers string
        $correct_answer = explode("|", $question_row-> correct);

        // define variables to pass to next page through post
        $num_answers = $_POST['num_answers']; // from POST or database
        $num_to_select = $_POST['num_to_select']; // from POST or database
        $hidden_post_vars = '';
        $visible = true;
        $form_action = "'_blank'";
        $show_given_answers = true;
        $given_answer = $submitted_answer;
        $show_correct_answers = true;
        $submit_button = '';

        include('generate_answers.php');

        // code to decide if the user's answer is taken as correct
        // Note: decision depends on lecturer and type of question
        $num_given_answers = count($given_answer);
        if ($num_given_answers == 0 || empty($submitted_answer))
          $reload = " You have not submitted any answers. <br />";
        else
        {
          $num_correct_answers = count($correct_answer);
          if ($num_to_select == 1)
            $is_user_correct = ($num_answered_correctly == 1);
          else if ($num_given_answers > $num_to_select)
            $is_user_correct = false;
          else if ($num_given_answers == $num_to_select)
            $is_user_correct = ($num_answered_correctly == $num_given_answers);
          else
            $is_user_correct = false;
	  
          if ($is_user_correct)
            $reload = " Your answer is correct. <br />";
          else
            $reload = " Your answer is not correct. <br />";
        }
      } // if $current_time > $endtime

      $question = str_replace("\n","<br/>",htmlspecialchars($question));
      $placeholder = array("##reload##", "##question##", "##answers##"
                  ,"##qid##","##courseName##","##date##","##date_long##","##qnumber##","##name##");
      $replace = array($reload, $question, $answers, $qid,
                        $courseName,$date,date("d M Y",strtotime($date)),$seq,$_SESSION['name']);
      echo str_replace($placeholder, $replace, file_get_contents('question-answered'));
    }
    else // if question_row is empty
    {
      $information = "Question ID '$qid' does not exist.";
      $placeholder = array("##information##","##name##");
      $replace = array($information,$_SESSION['name']);
      echo str_replace($placeholder, $replace, file_get_contents('error'));
    }
  }
  else // if $_POST['qid'] is not set
  {
    $information = "No question ID is set. Please provide a question ID.";
    $placeholder = array("##information##","##name##");
    $replace = array($information,$_SESSION['name']);
    echo str_replace($placeholder, $replace, file_get_contents('error'));
  }

?>
