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
  include_once ('dbCon.php');
  $user_type = ORM::for_table('user')
          ->select('type')
          ->where('username', $username)
          ->find_one();
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

  $all_date = ORM::for_table('questions')
              ->select('date')
              ->group_by('date')
              ->order_by_desc('date')
              ->find_many();
  if ($all_date->count() != 0) {
    foreach ($all_date as $date)
    {
      echo "<li><a href='questionlist.php?date=$date&courseName=$courseName'>$date</a></li>";
    } 
  }
  else {
    echo "<li>No content</li>";
  }
  ?>


  </ul>
  <form method="GET" action="questionlist.php"   
  <?php if($user_type == 'student') {
    echo 'style="visibility:hidden"'; }?>>
  <input type="text" name="courseName" style="visibility:hidden" value="<?php echo $courseName;?>"/></br>
  <input type="text" name="date" placeholder="yyyy-mm-dd"/>
  <input type="submit" class="button" value="add date"/>
  </form>
  </body>
</html>
