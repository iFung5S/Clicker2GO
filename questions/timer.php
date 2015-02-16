<?php

  $countdown = $endtime - $currenttime;

  $timer= str_replace("##time##", $countdown, file_get_contents('timer'));

?>
