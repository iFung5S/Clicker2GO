<?php
session_start();
if (isset($_SESSION['uid'])) {
  header('Location: ../');
  exit(0);
} 
if(isset($_GET['err']) && $_GET['err'] == 1)
{
  $message = "<span class='error'>Incorrect email or password.</span>";
  $select = "document.getElementById('username').select();";
}
else if(isset($_GET['err']) && $_GET['err'] == 2)
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
else if(isset($_GET['TIMEOUT']))
{
  $message = "<span class='error'>Your session has expired. Please login again.</span>";
  $select = "";
}
else
{
  $message = "";
  $select = "document.getElementById('username').select();";
}

if(isset($_GET['username']))
{
  $username = $_GET['username'];
}
else
{
  $username = "";
}

$placeholder = array("##message##", "##select##", "##username##");
$replace = array($message, $select, $username);

echo str_replace($placeholder, $replace, file_get_contents('login'));

?>
