<?php
  // Initialize session
  session_start();

  include_once ('../lib/dbCon.php');

  $username = $_POST['username'];
  $password = sha1($_POST['password']);

  $user = ORM::for_table('user')->find_one($username);
  if (!empty($user))
  {
    if ($password == $user->password)
    {
      $redirect = "<script>window.location.assign('../');</script>";
      $_SESSION['username'] = $username;
      $_SESSION['name'] = $user->name;
    }
    else
    {
      $redirect = "<script>window.location.assign('login.php?err=1');</script>";
    }
  }
  else
  {
    $redirect = "<script>window.location.assign('login.php?err=1');</script>";
  }

$placeholder = "##redirect##";
echo str_replace($placeholder, $redirect, file_get_contents('login_check.html',TRUE));

?>
