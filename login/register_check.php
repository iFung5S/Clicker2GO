<?php

  include_once ('../lib/dbCon.php');

  $username = $_POST['username'];
  $salt = generateRandomString();
  $password_salted = sha1($_POST['password'] . $salt);
  $name = $_POST['name'];
  $redirect = "";

  // Function to generte a random string
  function generateRandomString($length = 40) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

  $user = ORM::for_table('users')->where('username',$username)->find_one();
  if (!empty($user))
  {
     $redirect = "<script>window.location.assign('register.php?exist=1');</script>";
  }
  else if (!filter_var($username, FILTER_VALIDATE_EMAIL))
  {
     $redirect = "<script>window.location.assign('register.php?err=1');</script>";
  }
  else if (!preg_match("/^[a-zA-Z]+\s?[a-zA-Z]+$/",$name))
  {
     $redirect = "<script>window.location.assign('register.php?err=2');</script>";
  }
  else
  {
    $user = ORM::for_table('users')->create();
    $user->set(array(
            'username'=>$username,
            'password'=>$password_salted,
            'salt'=>$salt,
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
