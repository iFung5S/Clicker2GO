<?php

  // start countdown only if submitted by post
  if (isset($_POST['startTimer']))
  {
    $countdown = (int) ($_POST['startTimer']);
    $question_row->countdown = $countdown;
    
    // set the starttime in the database to time=now
    $question_row->starttime = date("Y-m-d H:i:s", time());
    $question_row->endtime = date("Y-m-d H:i:s", time() + $countdown);
    $question_row->save();

    $starttime = strtotime($question_row->starttime);
    $countdownstarted = true;

  }

  // reset countdown only if submitted by post
  else if (isset($_POST['resetcountdown']) && $_POST['resetcountdown'] == 1)
  {
    // set the starttime in the database to NULL
    $question_row->starttime = null;
    $question_row->save();

    $countdownstarted = false;
  }

  // reset all given answers from all users for the specific question
  if (isset($_POST['reset_answers']) && $_POST['reset_answers'] == 1)
  {
    $reset_answers = ORM::for_table('answers')
                     ->where('qid',$qid)
                     ->delete_many();
  }
      
      
  if (isset($_POST['extend']))
  {
    $question_row->endtime = date("Y-m-d H:i:s"
                         , strtotime($question_row->endtime) + $_POST['extend']);
    $question_row->save();
  }
        
  // check if countdown already started
  if ($question_row->starttime == NULL)
  {  
    $countdownstarted = false; 
  }
  else
  {
    $starttime = strtotime($question_row->starttime);
    $countdownstarted = (time() >= $question_row->starttime);
  }
  // this page url to refresh
  $thispage = "question-lecture.php?seq=$seq&qid=$qid";

  if ($countdownstarted)
  {
    $resetTimer = "<form action='$thispage' method='POST'>
                           <input name='resetcountdown' type='hidden' value=1 />
                           <input type='submit' value='Reset CountDown' >
                           </form>";
  }
  else
    $resetTimer = "";

  if ($countdownstarted)
  {
    $time_left = strtotime($question_row->endtime) - time();
    $timer_action = "";
    include('timer.php');
    $timer = "Timer ".$timer;
  }
  else
    $timer ="Timer not start"; 
  
  //start timer button to start timer with given value as countdown time
  //reset timer button only appear when counter started
  //extend timer button to extend questino end time that not make change on saved countdown value in db
  $set_timer = $timer. "<br>".  
               $resetTimer.
                          "<form action='$thispage' method='POST'>
                           <input name='reset_answers' type='hidden' value=1 />
                           <input type='submit' value='Clear given answers got' />
                           </form>    
                           <form action='$thispage' method='POST' >
                           <input name='startTimer' type='text' value ='$question_row->countdown'
                           style='width:40px'/>s
                           <input type='submit' value='Start timer' id='start_timer_btn'/>
                           </form>
                           <form action='$thispage' method='POST'>
                           <input name='extend' type='text' style='width:40px'/>
                           <input type='submit' value='Extend timer' />
                           </form>";
?>

