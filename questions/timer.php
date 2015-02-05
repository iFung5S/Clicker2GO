<?php

session_start();

include_once ('../lib/dbCon.php');

$id = 1; // Just hardcoded 1 for now, should get from embedding page

$time_s_e = ORM::for_table('questions')
            ->select('starttime')
            ->select('endtime')
            ->find_one($id);
if (!empty($time_s_e->starttime))
{
  $time = time();
  $endtime = strtotime($time_s_e->endtime);

  $countdown = $endtime - $time;

  echo str_replace("##time##", $countdown, file_get_contents('timer.html',TRUE));

}
else
{
  echo "Not started.";
}
?>
