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

    include_once('./config.inc.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $userValidate = "SELECT * FROM user WHERE username='$username'";  

    $search = mysqli_query($conn, $userValidate);
     
    if (mysqli_num_rows($search) > 0)
    {
      $row = mysqli_fetch_assoc($search);
      if ($password == $row['password'])
      {
        echo "<script>window.location.assign('./temp_home.html');</script>";
        $_SESSION['username'] = $username;
        $_SESSION['name'] = $row['name'];
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
   
    mysqli_close($conn);
  
?>

</body>
</html>
