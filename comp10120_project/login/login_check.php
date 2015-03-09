<?php
  // Initialize session
  session_start();

  include_once ('../lib/dbCon.php');

  $username = $_POST['username'];

  $user = ORM::for_table('users')->where('username',$username)->find_one();
  if (!empty($user))
  {
    $password = sha1($_POST['password'] . $user->salt);
    if ($password == $user->password)
    {
      $_SESSION['uid'] = $user->id;
      $_SESSION['name'] = $user->name;
      $type = ORM::for_table('type')
              ->join('u_type',array(
                    'type.id','=','u_type.id_t'))
              ->where('u_type.uid',$user->id)
              ->find_one();
      $_SESSION['type'] = $type->type;

      $redirect = "<script>window.location.assign('../');</script>";

    }
    else
    {
       $redirect = "<script>window.location.assign('login.php?err=1');</script>";
    }
  }
  else
  {
    $redirect = "<script>window.location.assign('login.php?err=1&username=$username');</script>";
  }

  if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $redirect = "<script>window.location.assign('login.php?err=2');</script>";
  }

  $placeholder = "##redirect##";
  echo str_replace($placeholder, $redirect, file_get_contents('page_only_title'));

?>
