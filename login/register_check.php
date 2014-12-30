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

    include('../sqlconnect.php');

    $username = $_POST['username'];
    $password = sha1($_POST['password']);
    $name = $_POST['name'];

    $userValidate = "SELECT * FROM user WHERE username='$username'";

    $result = mysqli_query($conn, $userValidate);

    if (mysqli_num_rows($result) != 0)
      {
       echo "<script>window.location.assign('register.php?exist=1');</script>";
      }
    else
     {
        $sql = "INSERT INTO user (username, password, name, type) VALUES ('$username','$password','$name','student')";

       if (mysqli_query($conn, $sql))
        {

        echo "<script>window.location.assign('login.php?register=1');</script>";
        }
       else
        {

        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
      }

?>

  </body>
</html>
