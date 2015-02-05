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
  include_once ('../dbCon.php');
  $user = ORM::for_table('user')->find_one($username);
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
              ->where('course',$courseName)
              ->group_by('date')
              ->order_by_desc('date')
              ->find_many();
  if ($all_date->count() != 0) {
    foreach ($all_date as $each_date)
    {
      $date = $each_date->date;
      echo "<li><a href='questionlist.php?date=$date&courseName=$courseName'>$date</a></li>";
    } 
  }
  else {
    echo "<li>No content</li>";
  }
  ?>


  </ul>
  <form method="GET" action="questionlist.php"   
  <?php if($user->type == 'student') {
    echo 'style="visibility:hidden"'; }?>>
  <input type="text" name="courseName" style="visibility:hidden" value="<?php echo $courseName;?>"/></br>
  <input type="text" name="date" placeholder="yyyy-mm-dd" required/>
  <input type="submit" class="button" value="add date"/>
  <p> <?php if(isset($_GET['err']) && $_GET['err'] == 1) { echo "<span class='error'>wrong date format</span>"; } ?> </p>
  </form>
  </body>
</html>