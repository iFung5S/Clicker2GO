<?php
if(isset($_GET['err']) && $_GET['err'] == 1)
{
  $message = "<span class='error'>Incorrect username or password.</span>";
}
else if(isset($_GET['logout']) && $_GET['logout'] == 1)
{
  $message = "<span class='correct'>Logout successfully.</span>";
}
else
{
  $message = "";
}

echo str_replace("##message##", $message, file_get_contents('login.html',TRUE));

?>
