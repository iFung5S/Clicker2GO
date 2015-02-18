<?php


  // Initialize session
  session_start();

  // Jump to login page if uid not set
  if (!isset($_SESSION['uid']))
  {
          header('Location: ../');
  }
  else
  {
    $uid = $_SESSION['uid'];
  }

  // connect to mysql
  include_once ('../lib/dbCon.php');

    $qid = $_GET['qid'];
    $question_row = ORM::for_table('questions')-> find_one($qid);
    $correct_answer = explode("|",$question_row-> correct);
    $question = $question_row-> question;
    $numbering_characters="ABCDEF";
    $answers = "";

    for ($i=1; $i<=6; $i++) 
    {
      
      $answer = $question_row->get('answer'.$i); 
      if (!empty($answer)) 
      {
        $isCorrect = "";
        if (in_array('answer'.$i, $correct_answer))
          $isCorrect = "<span style='font-size:12px;color:green;'>  (Correct)</span>";  
        $N=$numbering_characters[$i-1];
        
        $answers = $answers .
                    "<li>
                      $N. $answer  $isCorrect
                    </li>";
      }
    }

  include('graph.php');
  $comment = "<iframe frameborder='0' width='450' height='300'  src='comment.php?qid=$qid'></iframe>";
  $placeholder = array("##graph##", "##question##", "##answers##","##comment##");
  $replace = array($graph, $question, $answers,$comment);
  echo str_replace($placeholder, $replace, file_get_contents('question-lecture'));

?>
