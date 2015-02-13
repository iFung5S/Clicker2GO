<?php
// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: ../');
}
  $courseName= $_GET['courseName'];
  $username = $_SESSION['username'];
  include_once ('../lib/dbCon.php');
  $user = ORM::for_table('user')->find_one($username);

  //list date

  $all_date = ORM::for_table('questions')
              ->select('date')
              ->where('course',$courseName)
              ->group_by('date')
              ->order_by_desc('date')
              ->find_many();
  $date_list = "";

  if ($all_date->count() != 0) 
  {
    foreach ($all_date as $each_date)
    {
      $date = $each_date->date;
       //for student earliest access time is 9am at that date
      $min_time = implode(' ', array($date,'09:00:00'));
      if ($user->type == 'student' && time()<strtotime($min_time))
      {
        $date_list = $date_list."<li><a href='#'>$date</a><span style='font-size:12px;color:grey;'>  (Not Start)</span></li>"; 
      }
      else
      {
        $date_list = $date_list."<li><a href='questionlist.php?date=$date&courseName=$courseName'>$date</a></li>";
      }    
    } 
  }
  else 
  {
    $date_list = "<li>No content</li>";
  }

  $button = "<form method='GET' action='questionlist.php'>
             <input type='text' name='courseName' style='visibility:hidden' value='$courseName'/></br>
             <input type='text' name='date' placeholder='yyyy-mm-dd' required/>
             <input type='submit' class='button' value='add date'/></form>";

  if(isset($_GET['err']) && $_GET['err'] == 1) { 
    $button = $button."<p><span class='error'>wrong date format</span></p>"; 
  }

$placeholder = array("##courseName##","##date_list##", "##add_date##");
$replace = array($courseName,$date_list, "");

  if($user->type != 'student') {
    $replace = array($courseName,$date_list,$button);
  } 

echo str_replace($placeholder, $replace, file_get_contents('datePage'));
?>
