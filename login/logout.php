<?php

// Inialize session
session_start();

// Include database connection settings
include('../lib/dbCon.php');

// Delete session
session_unset();

include('page_only_title');

if (isset($_GET['TIMEOUT'])) {
  header('Location: login.php?TIMEOUT');
  exit(0);
} else {
  // Jump to login page
  header('Location: login.php?logout=1');
}
?>
