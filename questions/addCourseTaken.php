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


  $uid = $_SESSION['uid'];
  include_once ('../lib/dbCon.php');

  $courseName = $_POST['courseName'];

  if (preg_match("/^[a-zA-Z0-9]+$/",$courseName)) {

    $cuid = ORM::for_table('course_units')
            ->where('course',$courseName)
            ->find_one();

    if (empty($cuid)){
      $courseUnit = ORM::for_table('course_units')->create();
      $courseUnit->course = $courseName;
      $courseUnit->owner_uid = $uid;
      $courseUnit->save();
      $cuid = ORM::for_table('course_units')
            ->where('course',$courseName)
            ->find_one();
    }

    $user_courses = ORM::for_table('user_courses')
                    ->where(array(
                            'uid' => $uid,
                            'id_cu' => $cuid->id
                     ))
                    ->find_one();

    if (empty($user_courses)) {
      $u_course = ORM::for_table('user_courses')->create();
      $u_course->uid = $uid;
      $u_course->id_cu = $cuid->id;
      $u_course->save();
    }
    $redirect = "<script>window.location.assign('../index.php');</script>";
  }
  else {
    $redirect = "<script>window.location.assign('../index.php?err=1');</script>";
  }

  echo str_replace("##redirect##", $redirect, file_get_contents('../login/page_only_title'));

?>
