<!DOCTYPE HTML>
<html> 
<?php

// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: login/login.php');
}
?>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Adding | Clicker2GO</title>
    <link rel="stylesheet" href="./style.css">
  </head>
  <body class="homepage">

  <?php

    include_once ('../sqlconnect.php');

    $username = $_POST['username'];
    $courseName = $_POST['courseName'];

    $query = "SELECT course FROM user WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    $course = mysqli_fetch_assoc($result); 
     echo $course;
    $course = implode("|",array($course,$courseName));
    $sql = "UPDATE user SET course = '$course' WHERE username='$username'";

    if (mysqli_query($conn, $sql))
     {
      echo "<script>window.location.assign('../index.php');</script>";
     }
    else
     {
     echo "Error: " . $sql . "<br>" . mysqli_error($conn);
     }
 
   
?>

</body>
</html>
