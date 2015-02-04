<!DOCTYPE html>
<html>
<?php
// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: login/login.php');
}
  $courseName= $_GET['courseName'];
  $username = $_SESSION['username'];
  include_once ('../sqlconnect.php');
?>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Date List | Clicker2GO</title>
    <link rel="stylesheet" href="../style.css">
  </head>

  <body class="homepage">

  <ul>
  <?php
  //list course user have

  $dateQuery = "SELECT * FROM question WHERE (id IN (select max(id) from question WHERE (course='$courseName') GROUP BY date)) ORDER BY id";
  $result = mysqli_query($conn, $dateQuery);
  if (!empty($result)) {
  while($row = mysqli_fetch_assoc($result))
  {
    $date = $row['date'];
    echo '<li><a href="questionlist.php?date=$date&courseName=$courseName">$date</a></li>';
  } }
  ?>


  </ul>
  <form method="GET" action="questionlist.php">
  <input type="text" name="courseName" style="visibility:hidden" value="<?php echo $courseName;?>"/></br>
  <input type="text" name="date" value="date"/>
  <input type="submit" class="button" value="add date"/>
  </form>
  </body>
</html>
