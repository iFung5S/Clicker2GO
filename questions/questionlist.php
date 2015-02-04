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
  $date=$_GET['date'];
  include_once ('../sqlconnect.php');
  $query = "SELECT * FROM questions WHERE (course='$courseName' and date='$date')";
  $result = mysqli_query($conn, $query);
?>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Question List | Clicker2GO</title>
    <link rel="stylesheet" href="../style.css">
  </head>

  <body class="homepage">
      <div><span><?php echo $courseName; ?></span>>
           <span><?php echo $date; ?></span></div>
  <ol>
  <?php
  $i=1;
  while ($row = mysqli_fetch_assoc($result)) {
    $qid = $row['id'];
    echo "<li><a href='question-answer.php?qid=$qid'>Question $i</a></li>";
    $i++;
    }  ?>
  </ol>

  <form method="POST" action="createQuestion.php">
  <input type="text" name="courseName" style="visibility:hidden" value="<?php echo $courseName;?>"/>
  <input type="text" name="date" style="visibility:hidden" value="<?php echo $date;?>"/></br>
  <input type="submit" class="button" value="creat new"/>
  </form>
   
  </body>
</html>
