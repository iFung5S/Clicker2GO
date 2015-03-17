<?php

  // Initialize session
  session_start();

  // Jump to login page if uid not set
  if (!isset($_SESSION['uid'])) {
    header('Location: ../');
    exit(0);
  }
  else if (time() > $_SESSION['expiry'])
  {
    session_unset();
    header('Location: login.php?TIMEOUT');
    exit(0);
  } else
    $_SESSION['expiry'] = time() + 1800;

  include_once ('../lib/dbCon.php');

  $uid = $_SESSION['uid'];
  $user = ORM::for_table('users')->find_one($uid);
  $old = sha1($_POST['old'] . $user->salt);
  $password_salted = sha1($_POST['password2'] . $user->salt);
  $redirect = "";

  $user = ORM::for_table('users')->find_one($uid);
  if ($user->password == $old)
  {
    $user->set('password', $password_salted);
    $user->save();

    $check = ORM::for_table('users')->find_one($uid);
    if ($check->password == $password_salted)
      $redirect = "<script>window.location.assign('password.php?success=1');</script>";
    else
      $redirect = "<script>window.location.assign('password.php?err=2');</script>";
  }
  else
    $redirect = "<script>window.location.assign('password.php?err=1');</script>";

  echo str_replace("##redirect##", $redirect, file_get_contents('page_only_title'));

?>

