<?php

  // Initialize session
  session_start();

  // Jump to login page if username not set
  if (!isset($_SESSION['uid'])) {
        header('Location: ../');
  }

  include_once ('../lib/dbCon.php');
  $qid = 10;  //hardcoded now
  $counts = array();
  $correct = array();
  $labels =array("answer1"=>"A",
                 "answer2"=>"B",
                 "answer3"=>"C",
                 "answer4"=>"D",
                 "answer5"=>"E",
                 "answer6"=>"F");

  $question_row = ORM::for_table('questions')->find_one($qid);

  for ($i = 1; $i <= 6; $i++) // 6 are the maximum allowed answers
  {
    $answer = $question_row->get('answer'.$i);

    if(!empty($answer))         //check if answer-i exists
    {
      $count = ORM::for_table('answers')
              ->where(array(
               'qid' => $qid,
               'answer' => 'answer'.$i
                  ))
             ->count();

      $correct_A = explode("|",$question_row->correct);

      if (in_array('answer'.$i,$correct_A))
      {  array_push($correct,$labels['answer'.$i].' : '.$count);
      }

     array_push($counts,array(
                "label" => $labels['answer'.$i].' : '.$count,
                "count" => $count 
                ));
     }
  }

  $correct = json_encode($correct);

  $table = json_encode($counts);

  $place = array("##table##", "##correct##");
  $change = array($table, $correct);
  echo str_replace($place, $change, file_get_contents(dirname(__FILE__).'/graph'));

 ?>
