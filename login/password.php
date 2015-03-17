<?php

  session_start();

  if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit(0);
  }
  else if (time() > $_SESSION['expiry'])
  {
    session_unset();
    header('Location: login.php?TIMEOUT');
    exit(0);
  } else
    $_SESSION['expiry'] = time() + 1800;

$message = "";
$file = "password";
if (isset($_GET['err']))
{
  if ($_GET['err'] == 1)
    $message = "Incorrect old password!";
  if ($_GET['err'] == 2)
    $message = "Sorry, error occurred.";
}
else if (isset($_GET['success']))
{
  if ($_GET['success'] == 1)
    $file = "password_success";
}
else
  $message = "<br />";
echo str_replace("##message##", $message, file_get_contents($file));

?>

