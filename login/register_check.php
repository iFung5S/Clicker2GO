<?php

  include_once ('../lib/dbCon.php');

  $username = $_POST['username'];
  $password = sha1($_POST['password']);
  $name = $_POST['name'];
  $redirect = "";

  $user = ORM::for_table('users')->where('username',$username)->find_one();
  if (!empty($user))
  {
     $redirect = "<script>window.location.assign('register.php?exist=1');</script>";
  }
  else if (!preg_match("/^[a-zA-Z]\w{4,15}$/",$username)) 
  {
     $redirect = "<script>window.location.assign('register.php?exist=2');</script>";
  }
  else if (!preg_match("/^[a-zA-Z_]+\s?[a-zA-Z_]+$/",$name)) 
  {
     $redirect = "<script>window.location.assign('register.php?exist=3');</script>";
  }
  else
  {
    $user = ORM::for_table('users')->create();
    $user->set(array(
            'username'=>$username,
            'password'=>$password,
            'name'=>$name
           ));
    $user->save();
    $type = ORM::for_table('u_type')->create();
    $uid = ORM::for_table('users')->where('username',$username)->find_one()->id;
    $type->set(array(
              'uid' => $uid,
              'id_t' => '1'
              ));
    $type->save();
    $redirect = "<script>window.location.assign('login.php?register=1');</script>";
   }

  echo str_replace("##redirect##", $redirect, file_get_contents('page_only_title'));

?>
