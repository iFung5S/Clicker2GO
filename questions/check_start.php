<?php
  include_once ('../lib/dbCon.php');
  if (isset($_POST['qid'])) {
    $qid = $_POST['qid'];
    $question_row = ORM::for_table('questions')-> find_one($qid);
    $starttime = $question_row-> starttime;
    if (is_null($starttime) || time() < strtotime($starttime))
      $start = false;
    else
      $start = true;
    echo $start;
  }
?>
