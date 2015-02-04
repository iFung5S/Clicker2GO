<!DOCTYPE html>
<?php
// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: ../login/login.php');
}
  $courseName= $_GET['courseName'];
  $username = $_SESSION['username'];
  include_once ('../sqlconnect.php');
  $query = "SELECT * FROM user WHERE username='$username'";
  $row_user = mysqli_fetch_assoc(mysqli_query($conn, $query));
?>
<html>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Date List | Clicker2GO</title>
    <link rel="stylesheet" href="../style.css">
  </head>

  <body class="homepage">
    <div><span><a href='../index.php'>Home</a></span>>
           <span><?php echo $courseName; ?></span></div>
  <ul>
  <?php
  //list date

  $dateQuery = "SELECT * FROM questions WHERE (id IN (SELECT min(id) FROM questions WHERE (course='$courseName') GROUP BY date)) ORDER BY date DESC ";
  $result = mysqli_query($conn, $dateQuery);
  if (mysqli_num_rows($result) != 0) {
  while($row_question = mysqli_fetch_assoc($result))
  {
    $date = $row_question['date'];
    echo "<li><a href='questionlist.php?date=$date&courseName=$courseName'>$date</a></li>";
  } }
 else {
   echo "<li>No content</li>";
  }
  ?>


  </ul>
  <form method="GET" action="questionlist.php"   
  <?php if($row_user['type'] == 'student') {
    echo 'style="visibility:hidden"'; }?>>
  <input type="text" name="courseName" style="visibility:hidden" value="<?php echo $courseName;?>"/></br>
  <input type="text" name="date" placeholder="yyyy-mm-dd"/>
  <input type="submit" class="button" value="add date"/>
  </form>
  </body>
</html>
