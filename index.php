<?php
// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: login/login.php');
}
  include_once ('dbCon.php');
  $username = $_SESSION['username'];

  $name =  $_SESSION['name'];

  //list course user have
  $user = ORM::for_table('user')->find_one($username);
  $course = $user->course;
  $list_course = "";

  if (empty($course)){
   $list_course = '<li>No course now</>';
  }
  else {
   $course = explode("|",$course);
   sort($course);
    for ($i=0;$i<count($course);$i++) {
      $courseName = $course[$i];
      $list_course = $list_course . "<li><a href='questions/datePage.php?courseName=$courseName'>$courseName</a></li>";    
      }
  }

  $add_course = "";

  if($user->type == 'student') {
    $add_course = $add_course . "<select name='courseName' required>";
    $add_course = $add_course . "<option value='' disabled>--Select Course--</option>";
    $questions_course = ORM::for_table('questions')
                        ->select('course')
                        ->group_by('course')
                        ->order_by_asc('course')
                        ->find_many();
    foreach ($questions_course as $each_course) {
      $courseName = $each_course->course;
      $add_course = "<option value='$courseName'";
      if (in_array($courseName,$course))
      {
        $add_course = $add_course . " disabled";
      }
      $add_course = $add_course . ">$courseName</option>";
    }
    $add_course = $add_course . "</select><br />";
  }
  else {
    $add_course = $add_course . "<input type='text' name='courseName' required/><br />";
    if(isset($_GET['err']) && $_GET['err'] == 1) {
      $add_course = $add_course . "<p><span class='error'>only letters and numbers</span></p>"; }
  }

$placeholder = array("##name##", "##list_course##", "##add_course##");
$replace = array($name, $list_course, $add_course);

echo str_replace($placeholder, $replace, file_get_contents('index',TRUE));

?>
