<?php

session_start();

  // Jump to login page if username not set
  if (!isset($_SESSION['username'])) {
        header('Location: ../');
  }

include_once ('../lib/dbCon.php');

  $qid = $_GET['qid']; // Just hardcoded 1 for now, should get from embedding page

$time_s_e = ORM::for_table('questions')
            ->select('starttime')
            ->select('endtime')
            ->find_one($qid);
if (!empty($time_s_e->starttime))
{
  $time = time();
  $endtime = strtotime($time_s_e->endtime);

  $countdown = $endtime - $time;

  echo str_replace("##time##", $countdown, file_get_contents('timer',TRUE));

}
else
{
  echo "Not started.";
}
?>
