<!DOCTYPE HTML>
<html> 
<head>
    <meta charset="utf-8">
    <title>Login message | Clicker2GO</title>
</head>
<body>
<?php

    include_once(config.inc.php);

    $username = $_POST['username'];
    $password = $_POST['password'];


    $userValidate = "SELECT * FROM user 
    WHERE username='$username'";  

    $search = mysqli_query($conn, $userValidate);
     
    if (mysqli_num_rows($search)>0)
      {
       $row = mysqli_fetch_assoc($userValidate);



       if ($password == $row["password"])
         {
          echo "<script>location.href='./temp_home.html';</script>"; 
         }
       else
         {
           echo "Wrong password";
           echo '<a href="./login.php">Back to the login page</a>';  
         }
      }
    else 
     {
       echo "User not exist";
       echo '<a href="./login.php">Back to the login page</a>';
       
      }

   

    mysqli_close($conn);
  
?>

</body>
</html>
