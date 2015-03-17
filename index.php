<?php
// Initialize session
session_start();

// Jump to login page if uid not set
if (!isset($_SESSION['uid'])) {
  header('Location: login/login.php');
  exit(0);
}
else if (time() > $_SESSION['expiry'])
{
  session_unset();
  header('Location: login/login.php?TIMEOUT');
  exit(0);
} else
  $_SESSION['expiry'] = time() + 1800;


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
   $list_course = '<div>No courses now.</div>';
  }
  else {

    foreach ($courses as $course) {
      $courseName = $course->course;
      //for remove course
      if (in_array(array("type"=>"Student"),$type)) {
        $confirm = "javascript:if(confirm('Do you want to remove course unit $courseName?'))location='questions/removeCourse.php?courseName=$courseName'";
      } else {
        $owner = ORM::for_table('course_units')
                 ->where('course',$courseName)
                 ->find_one()
                 ->owner_uid;
        if ($owner == $uid) {
          $confirm = "javascript:if(confirm('Do you want to delete course unit $courseName? (Also related questions)'))location='questions/removeCourse.php?courseName=$courseName'";
        } else {
          $confirm = "javascript:if(confirm
                     ('Do you want to remove course unit $courseName?(Course unit and related questions will not be deleted as you are not the owner of this course)'))
                     location='questions/removeCourse.php?courseName=$courseName'";
        }
      }

      $list_course = $list_course . "<div style='margin-bottom:0'><a href='questions/datePage.php?courseName=$courseName'>$courseName</a> <span class='redCross'><a href='#' onClick=\"$confirm\">x</a></span></div> <div class=rectangle> </div>";
      }
  }

  $add_course = "";

  if(in_array(array("type"=>"Student"),$type)) {
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
    $selected = "";
  }
  else {
    $add_course = $add_course . "<div class='form-item'><input type='text' id='courseName' name='courseName' placeholder='New Course Name' required/></div>";
    if(isset($_GET['err']) && $_GET['err'] == 1)
    {
      $add_course = $add_course . "<p><span class='error'>Course Name must be alphanumeric.</span></p>";
      $selected = "<script>document.getElementById('courseName').select();</script>";
    }
   else
      $selected = "";
  }

if (in_array(array("type"=>"Administrator"),$type))
  $admin = "<p class='normalTextStyle'><a href='admin/manage.php'>Administrator Management Panel</a></p>";
else
  $admin = "";


$placeholder = array("##name##", "##list_course##", "##add_course##", "##admin_panel##");
$replace = array($name, $list_course, $add_course.$selected, $admin);

echo str_replace($placeholder, $replace, file_get_contents('index'));

?>
