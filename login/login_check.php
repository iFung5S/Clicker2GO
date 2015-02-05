<!DOCTYPE HTML>
<html> 
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Login... | Clicker2GO</title>
    <link rel="stylesheet" href="./style.css">
  </head>
  <body class="homepage">

  <?php

    // Initialize session
    session_start();

  include_once ('../dbCon.php');

    $username = $_POST['username'];
    $password = sha1($_POST['password']);

    $user = ORM::for_table('user')->find_one($username);
    if (!empty($user))
    {
      if ($password == $user->password)
      {
        echo "<script>window.location.assign('../');</script>";
        $_SESSION['username'] = $username;
        $_SESSION['name'] = $user->name;
      }
      else
      {
        echo "<script>window.location.assign('login.php?err=1');</script>";  
      }
    }
    else 
    {
      echo "<script>window.location.assign('login.php?err=1');</script>";
    }
   
?>

</body>
</html>