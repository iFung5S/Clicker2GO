<?php

  $placeholder = array("##time_left##", "##timer_action##");
  $replace = array($time_left, $timer_action);
  $timer= str_replace($placeholder, $replace, file_get_contents('timer'));

?>
