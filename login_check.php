<!DOCTYPE HTML>
<html> 
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Login message | Clicker2GO</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body class="homepage">
  <?php

    include_once('config.inc.php');

    $username = $_POST['username'];
    $password = $_POST['password'];
    $conn = mysqli_connect($database_host, $database_user, $database_pass,$group_dbnames[0]);

    $userValidate = "SELECT * FROM user 
    WHERE username='$username'";  

    $search = mysqli_query($conn, $userValidate);
     
    if (mysqli_num_rows($search)>0)
      {
       $row = mysqli_fetch_assoc($search);

       if ($password == $row["password"])
         {
          echo "<script>location.href='./temp_home.html';</script>"; 
         }
       else
         {
           echo "Wrong password";
           echo '<br>';
           echo '<a href="./login.php">Back to the login page</a>';  
         }
      }
    else 
     {
       echo "User not exist";
       echo '<br>';
       echo '<a href="./login.php">Back to the login page</a>';
       
      }

   
    mysqli_close($conn);
  
?>

</body>
</html>
