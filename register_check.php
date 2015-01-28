<!DOCTYPE HTML>
<html> 
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Register message | Clicker2GO</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body class="homepage">
  <?php

    include_once('config.inc.php');

    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];


    $conn = mysqli_connect($database_host, $database_user, $database_pass,$group_dbnames[0]);

    $userValidate = "SELECT * FROM user 
    WHERE username='$username'";  

    $search = mysqli_query($conn, $userValidate);
     
    if (mysqli_num_rows($search)>0)
      {
       echo "User already existed";
       echo '<br>';
       echo "<script type='javascript'>windowsetTimeout(\"window.location.href='register.php'\",2000);</script>"; 
         
      }
    else 
     {
        $sql = "INSERT INTO user (username, password, name) 
       VALUES
       ('$username','$password','$name')";

       if (mysqli_query($conn, $sql))
        {

        echo "Registration successfully"; 
        echo '<br>';
        echo '<a href="./login.php">Go to the login page</a>';
        }
       else
        {

        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
       
      }

   
    mysqli_close($conn);
  
  ?>

  </body>
</html>
