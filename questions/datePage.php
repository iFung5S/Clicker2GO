<?php
// Initialize session
session_start();

// Jump to login page if uid not set
if (!isset($_SESSION['uid'])) {
  header('Location: ../');
  exit(0);
}
else if (time() > $_SESSION['expiry'])
{
  session_unset();
  header('Location: ../login/login.php?TIMEOUT');
  exit(0);
} else
  $_SESSION['expiry'] = time() + 1800;


if(isset($_GET['courseName'])){

  $courseName = $_GET['courseName'];

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
       // for student: earliest access time is 9am at that date
      $min_time = implode(' ', array($date,'09:00:00'));

      // for remove date
      if (!in_array(array("type"=>"Student"),$_SESSION['type'])) {
        $confirm = "javascript:if(confirm('Do you want to remove date " . date("d M Y",strtotime($date)) . " and associated questions?'))location='removeQuestion.php?courseName=$courseName&date=$date'";
        $date_list .= "<div style='margin-bottom:0'><a href='questionlist.php?date=$date&amp;courseName=$courseName'>" . date("d M Y",strtotime($date)) . "</a>  <span class='redCross'><a href='#' onclick=\"$confirm\">x</a></span></div><div class='rectangle'> </div>";
      }
      else if (in_array(array("type"=>"Student"),$_SESSION['type'])
                && time()<strtotime($min_time))
        $date_list .= "<div style='margin-bottom:0'><a href='#'>" . date("d M Y",strtotime($date)) . "</a><span style='font-size:12px;color:grey;'>  (Not Started)</span></div><div class='rectangle'> </div>";
      else
        $date_list .= "<div style='margin-bottom:0'><a href='questionlist.php?date=$date&amp;courseName=$courseName'>" . date("d M Y",strtotime($date)) . "</a></div><div class='rectangle'> </div>";
    }
  }
  else
    $date_list = "No content.";

  $button = "<form method='GET' action='questionlist.php'>
             <input type='hidden' name='courseName' value='$courseName' /><br />
             <div class='form-item'><input type='text' name='date' placeholder='New Date in YYYY-MM-DD' required /></div>
             <div class='button-panel'><input type='submit' class='button' value='Add Date' /></div></form>";

  if(isset($_GET['err']) && $_GET['err'] == 1)
    $button = "<div class='error'><p>Incorrect date format.</p></div>" . $button;

  $placeholder = array("##courseName##","##date_list##", "##add_date##","##name##");
  $replace = array($courseName,$date_list, "",$_SESSION['name']);

  if(!in_array(array("type"=>"Student"),$_SESSION['type']))
    $replace = array($courseName,$date_list,$button,$_SESSION['name']);

  echo str_replace($placeholder, $replace, file_get_contents('datePage'));

//for errors
  }
  else
{
    $information = "The course '$courseName' does not exist.";
    $placeholder = array("##information##","##name##");
    $replace = array($information,$_SESSION['name']);
    echo str_replace($placeholder, $replace, file_get_contents('error'));
  }
}
else
{
  $information = "The course name is empty.";
  $placeholder = array("##information##","##name##");
  $replace = array($information,$_SESSION['name']);
  echo str_replace($placeholder, $replace, file_get_contents('error'));
}

?>
