<?php

session_start();

include('../sqlconnect.php');

$id = 1; // Just hardcoded 1 for now, should get from embedding page

$query = "SELECT starttime, endtime FROM questions WHERE id='$id'";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
if ($row['starttime'] != NULL)
{
  $time = time();
  $endtime = strtotime($row['endtime']);

  $countdown = $endtime - $time;
  
  echo str_replace("#time#",$countdown , include('timer.html'));

}
else
{
  echo "Not started.";
}
?>
