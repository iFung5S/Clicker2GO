<?php

  $countdown = $endtime - $currenttime;
  $place = array("##time##","##action##");
   $change = array($countdown,$action);
  $timer= str_replace($place, $change, file_get_contents('timer'));

?>
