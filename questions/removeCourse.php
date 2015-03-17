<?php
// Initialize session
session_start();

// Jump to login page if uid not set
  if (!isset($_SESSION['uid']))
  {
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

  $courseName = $_GET['courseName'];

  $cuid = ORM::for_table('course_units')
            ->where('course',$courseName)
            ->find_one();

    if (!in_array(array("type"=>"Student"),$_SESSION['type'])
            && $cuid->owner_uid == $uid){

      $cuid = $cuid->delete();
    }
    else {
    $delete_user_course = ORM::for_table('user_courses')
                          ->where(array(
                                 'uid' => $uid,
                                 'id_cu' => $cuid->id
                             ))
                          ->delete_many();
   }

    $redirect = "<script>window.location.assign('../index.php');</script>";


  echo str_replace("##redirect##", $redirect, file_get_contents('../login/page_only_title'));

?>
