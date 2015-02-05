<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Register message | Clicker2GO</title>
    <link rel="stylesheet" href="../style.css">
  </head>
  <body class="homepage">
  <?php

  include_once ('../lib/dbCon.php');

    $username = $_POST['username'];
    $password = sha1($_POST['password']);
    $name = $_POST['name'];

    $user = ORM::for_table('user')->find_one($username);
    if (!empty($user))
    {
       echo "<script>window.location.assign('register.php?exist=1');</script>";
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

      echo "<script>window.location.assign('login.php?register=1');</script>";
     }
  ?>

  </body>
</html>
