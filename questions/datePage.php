<?php
// Initialize session
session_start();

// Jump to login page if uid not set
if (!isset($_SESSION['uid'])) {
        header('Location: ../');
}

if(isset($_GET['courseName'])){

  $courseName= $_GET['courseName'];

  include_once ('../lib/dbCon.php');

  //list date
  $cuid = ORM::for_table('course_units')
        ->where('course',$courseName)
        ->find_one();

 if (!empty($cuid)) {

  $cuid = $cuid->id;
  $all_date = ORM::for_table('questions')
              ->select('date')
              ->where('id_cu',$cuid)
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

      //for remove date
      if ($_SESSION['type'] != 'Student') {
        $confirm = "javascript:if(confirm('Do you want to remove date $date? (Also related questions)'))location='removeQuestion.php?courseName=$courseName&date=$date'";
        $date_list = $date_list."<li><a href='questionlist.php?date=$date&amp;courseName=$courseName'>$date</a>  <a href='javascript:void(0)' style='font-size:18px;color:red;text-decoration:none;' onclick=\"$confirm\">x</a></li>";
      }
      else if ($_SESSION['type'] == 'Student' && time()<strtotime($min_time))
      {
        $date_list = $date_list."<li><a href='#'>$date</a><span style='font-size:12px;color:grey;'>  (Not Start)</span></li> <br> <div id=rectangle> </div> <br>"; 
      }
      else
      {
        $date_list = $date_list."<li><a href='questionlist.php?date=$date&amp;courseName=$courseName'>$date</a></li> <br> <div id=rectangle> </div> <br>";
      }    
    } 
  }
  else 
  {
    $date_list = "<li>No content</li>";
  }

  $button = "<form method='GET' action='questionlist.php'>
             <input type='hidden' name='courseName' value='$courseName'/><br/>
             <input type='text' name='date' placeholder='YYYY-MM-DD' required/>
             <input type='submit' class='button' value='Add Date'/></form>";

  if(isset($_GET['err']) && $_GET['err'] == 1) { 
    $button = $button."<p><span class='error'>wrong date format</span></p>"; 
  }

$placeholder = array("##courseName##","##date_list##", "##add_date##","##name##");
$replace = array($courseName,$date_list, "",$_SESSION['name']);

  if($_SESSION['type'] != 'Student') {
    $replace = array($courseName,$date_list,$button,$_SESSION['name']);
  } 

  echo str_replace($placeholder, $replace, file_get_contents('datePage'));

//for errors
 }
 else {
  $information = "The course '$courseName' is not exist.";
  $placeholder = array("##information##","##name##");
  $replace = array($information,$_SESSION['name']);
  echo str_replace($placeholder, $replace, file_get_contents('error'));
 }
}
else {
  $information = "The course name is empty.";
  $placeholder = array("##information##","##name##");
  $replace = array($information,$_SESSION['name']);
  echo str_replace($placeholder, $replace, file_get_contents('error'));
}

?>