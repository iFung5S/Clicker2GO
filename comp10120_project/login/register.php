<?php
if(isset($_GET['exist']) && $_GET['exist'] == 1)
  $message =  "Username already exist.";
else if (isset($_GET['err']) && $_GET['err'] == 1)
  $message =  "Invalid email.";
else if (isset($_GET['err']) && $_GET['err'] == 2)
  $message =  "Name can only contain letters and not more than one space.";
else
  $message = "<br/>";
echo str_replace("##message##", $message, file_get_contents('register'));

?>
