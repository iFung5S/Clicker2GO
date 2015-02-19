<?php
if(isset($_GET['exist']) && $_GET['exist'] == 1)
  $message =  "Username already exist.";
else
  $message = "<br/>";
echo str_replace("##message##", $message, file_get_contents('register'));

?>
