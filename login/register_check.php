<?php

  include_once ('../lib/dbCon.php');

  $username = $_POST['username'];
  $password = sha1($_POST['password']);
  $name = $_POST['name'];
  $redirect = "";

  $user = ORM::for_table('user')->find_one($username);
  if (!empty($user))
  {
     $redirect = "<script>window.location.assign('register.php?exist=1');</script>";
  }
  else
  {
    $user = ORM::for_table('user')->create();
    $user->set(array(
            'username'=>$username,
            'password'=>$password,
            'name'=>$name
           ));
    $user->save();

    $redirect = "<script>window.location.assign('login.php?register=1');</script>";
   }

  echo str_replace("##redirect##", $redirect, file_get_contents('page_only_title'));

?>
