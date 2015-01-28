<?php
$database_host = "dbhost.cs.man.ac.uk";
$database_user = "mbax4cf2";
$database_pass = "1234567890";

$group_dbnames = array(
    "2014_comp10120_x4",
);

// Create connection
$conn = mysqli_connect($database_host, $database_user, $database_pass, $group_dbnames[0]);

// Check connection
if(!$conn)
  die(mysqli_connect_error());

?>
