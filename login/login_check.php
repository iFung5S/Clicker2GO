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

    include_once "../config.inc.php";

    echo $username = $_POST['username'];
    echo $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE username='$username'";

    $result = mysqli_query($conn, $query);
     
    if (mysqli_num_rows($result) > 0)
    {
      $row = mysqli_fetch_assoc($result);
      if ($password == $row['password'])
      {
        echo "<script>window.location.assign('../');</script>";
        $_SESSION['username'] = $username;
        $_SESSION['name'] = $row['name'];
      }
      else
      {
 //       echo "<script>window.location.assign('login.php?err=1');</script>";  
      }
    }
    else 
    {
   //   echo "<script>window.location.assign('login.php?err=1');</script>";
    }
   
?>

</body>
</html>
