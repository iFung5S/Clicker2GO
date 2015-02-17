<?php
// Initialize session
session_start();

// Jump to login page if uid not set
if (!isset($_SESSION['uid'])) {
        header('Location: login/login.php');
}
  include_once ('lib/dbCon.php');
  $uid = $_SESSION['uid'];

  $name =  $_SESSION['name'];

  //list course user have
  $courses = ORM::for_table('course_units')
             ->select('course_units.course')
             ->join('user_courses',array(
                    'course_units.id','=','user_courses.id_cu'))
             ->where('user_courses.uid',$uid)
             ->order_by_asc('course_units.course')
             ->find_many();

  $list_course = "";
  $exist = $courses->count();
  if ($exist == 0){
   $list_course = '<li>No course now</li>';
  }
  else {

    foreach ($courses as $course) {
      $courseName = $course->course;
      $list_course = $list_course . "<li><a href='questions/datePage.php?courseName=$courseName'>$courseName</a></li>";
      }
  }

  $add_course = "";

  if($_SESSION['type'] == 'student') {
    $add_course = $add_course . "<select name='courseName' required>";
    $add_course = $add_course . "<option value='' >--Select Course--</option>";

    $all_course = ORM::for_table('course_units')
                        ->select('course')
                        ->order_by_asc('course')
                        ->find_many();

    foreach ($all_course as $each_course) {
      $courseName = $each_course->course;
      $add_course = $add_course . "<option value='$courseName'";
      $check = false;
    foreach ($courses as $course) {
     if($course->course == $courseName)
       $check = true; }
      if (!empty($courses) && !empty($check))
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

echo str_replace($placeholder, $replace, file_get_contents('index'));

?>
