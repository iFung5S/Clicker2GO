<?php
if(isset($_GET['exist']))
{
  if($_GET['exist'] == 1)
    $message =  "Username already exist.";
  else if ($_GET['exist'] == 2)
    $message =  "Username can only contain letters,numbers and underscore, start with letter, length 5-16.";
  else if ($_GET['exist'] == 3)
    $message =  "Name can only contain letters underscore and at most one space.";
}
else
  $message = "<br/>";
echo str_replace("##message##", $message, file_get_contents('register'));

?>
