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
  $date=$_GET['date'];
  include_once ('../sqlconnect.php');
  $query = "SELECT * FROM questions WHERE (course='$courseName' and date='$date')";
  $result = mysqli_query($conn, $query);
  $query_user = "SELECT * FROM user WHERE username='$username'";
  $row_user = mysqli_fetch_assoc(mysqli_query($conn, $query_user));
?>
<html>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Question List | Clicker2GO</title>
    <link rel="stylesheet" href="../style.css">
  </head>

  <body class="homepage">
      <div>
      <span><a href='../index.php'>Home</a></span>>
      <span><a href="datePage.php?courseName=<?php echo $courseName; ?>"><?php echo $courseName; ?></a></span>>
      <span><?php echo $date; ?></span></div>
  <ol>
  <?php
  $i=1;
  if (mysqli_num_rows($result) != 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $qid = $row['id'];
    echo "<li><a href='question-answer.php?qid=$qid'>Question $i</a></li>";
    $i++;
    }}
  else {
    echo "<li>No Question</br>(This date page will not be saved if no question added)</li>"; 
  } 
  ?>
  </ol>

  <form method="POST" action="createQuestion.php"
  <?php if($row_user['type'] == 'student') {
    echo 'style="visibility:hidden"'; }?> >
  <input type="text" name="courseName" style="visibility:hidden" value="<?php echo $courseName;?>"/>
  <input type="text" name="date" style="visibility:hidden" value="<?php echo $date;?>"/></br>
  <input type="submit" class="button" value="creat new"/>
  </form>
   
  </body>
</html>
