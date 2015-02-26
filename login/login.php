<?php
if(isset($_GET['err']) && $_GET['err'] == 1)
{
  $message = "<span class='error'>Incorrect email or password.</span>";
  $select = "document.getElementById('username').select();";
}
else if(isset($_GET['err']) && $_GET['err'] == 1)
{
  $message = "<span class='error'>Invalid email.</span>";
  $select = "document.getElementById('username').select();";
}
else if(isset($_GET['logout']) && $_GET['logout'] == 1)
{
  $message = "<span class='correct'>Logout successfully.</span>";
  $select = "";
}
else if(isset($_GET['register']) && $_GET['register'] == 1)
{
  $message = "<span class='correct'>Account created successfully.</span>";
  $select = "document.getElementById('username').select();";
}
else
{
  $message = "";
  $select = "document.getElementById('username').select();";
}

$placeholder = array("##message##", "##select##");
$replace = array($message, $select);

echo str_replace($placeholder, $replace, file_get_contents('login'));

?>
