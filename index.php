<?php
// Initialize session
session_start();

// Jump to login page if uid not set
if (!isset($_SESSION['uid'])) {
        header('Location: login/login.php');
}
  include_once ('lib/dbCon.php');
  $uid = $_SESSION['uid'];
  $type = $_SESSION['type'];
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
   $list_course = '<li>No courses now.</li>';
  }
  else {

    foreach ($courses as $course) {
      $courseName = $course->course;
      //for remove course
      if ($_SESSION['type'] == 'Student') {
        $confirm = "javascript:if(confirm('Do you want to remove course unit $courseName?'))location='questions/removeCourse.php?courseName=$courseName'";
      } else {
        $confirm = "javascript:if(confirm('Do you want to delete course unit $courseName? (Also related questions)'))location='questions/removeCourse.php?courseName=$courseName'";
      }

      $list_course = $list_course . "<li><a href='questions/datePage.php?courseName=$courseName'>$courseName</a> <a href='javascript:void(0)' style='font-size:18px;color:red;text-decoration:none;' onclick=\"$confirm\">x</a></li>";
      }
  }

  $add_course = "";

  if($_SESSION['type'] == 'Student') {
    $add_course = $add_course . "<select class='form-item' name='courseName' required>";
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
      if (!empty($courses) && $check)
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
      $add_course = $add_course . "<p><span class='error'>Course Name must be alphanumeric.</span></p>"; }
  }

if ($type == 'Administrator')
  $admin = "<p class='normalTextStyle'><a href='admin/manage.php'>Administrator Management Panel</a></p>";
else
  $admin = "";


$placeholder = array("##name##", "##list_course##", "##add_course##", "##admin_panel##");
$replace = array($name, $list_course, $add_course, $admin);

echo str_replace($placeholder, $replace, file_get_contents('index'));

?>
