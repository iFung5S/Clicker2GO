<?php

  if (!isset($_SESSION['uid'])) {
        header('Location: '.dirname(__FILE__).'/login.php');
  } 
  else if (time() > $_SESSION['expiry'])
  {
    session_unset();
    header('Location: '.dirname(__FILE__).'/login.php?TIMEOUT');
  } else {
    $_SESSION['expiry'] = time() + 60;
  }

?>
