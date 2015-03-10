<?php
// Initialize session
session_start();

// Jump to login page if uid not set
if (!isset($_SESSION['uid'])) {
        header('Location: ../');
}
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
