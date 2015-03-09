<?php

include('../config.inc.php');

// Create connection
$conn = mysqli_connect($database_host, $database_user, $database_pass, $group_dbnames[0]);

// Check connection
if(!$conn)
  die(mysqli_connect_error());

?>