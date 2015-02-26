<?php

  // Initialize session
  session_start();

  // Jump to login page if uid not set
  if (!isset($_SESSION['uid'])) {
          header('Location: ../');
  }

  include_once ('../lib/dbCon.php');

  $uid = $_SESSION['uid'];
  $old = sha1($_POST['old']);
  $password = sha1($_POST['password2']);
  $redirect = "";

  $user = ORM::for_table('users')->find_one($uid);
  if ($user->password == $old)
  {
    $user->set('password', $password);
    $user->save();

    $check = ORM::for_table('users')->find_one($uid);
    if ($check->password == $password)
      $redirect = "<script>window.location.assign('password.php?success=1');</script>";
    else
      $redirect = "<script>window.location.assign('password.php?err=2');</script>";
  }
  else
    $redirect = "<script>window.location.assign('password.php?err=1');</script>";

  echo str_replace("##redirect##", $redirect, file_get_contents('page_only_title'));

?>

