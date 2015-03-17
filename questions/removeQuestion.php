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

if(!in_array(array("type"=>"Student"),$_SESSION['type'])) {
    include_once ('../lib/dbCon.php');
  if (isset($_GET['courseName']) && isset($_GET['date']))
  {
    $courseName = $_GET['courseName'];
    $date = $_GET['date'];

    $cuid = ORM::for_table('course_units')
            ->where('course',$courseName)
            ->find_one()->id;
    $delete_questions = ORM::for_table('questions')
                        ->where(array(
                                'date' => $date,
                                'id_cu' => $cuid
                           ))
                        ->delete_many();

    $redirect = "<script>window.location.assign('datePage.php?courseName=$courseName');</script>";

    echo str_replace("##redirect##", $redirect, file_get_contents('../login/page_only_title'));
   }
   else if (isset($_GET['qid']))
   {
     $qid = $_GET['qid'];
     $delete_question = ORM::for_table('questions')
                        ->find_one($qid)
                        ->delete();

    $redirect = "<script>javascript:history.back();</script>";

    echo str_replace("##redirect##", $redirect, file_get_contents('../login/page_only_title'));
  }
  else {
    $information = "Something unforseen has happended.";
    $placeholder = array("##information##","##name##");
    $replace = array($information,$_SESSION['name']);
    echo str_replace($placeholder, $replace, file_get_contents('error'));
  }
}
else {
  $information = "Sorry, you don't have permission for this action.";
  $placeholder = array("##information##","##name##");
  $replace = array($information,$_SESSION['name']);
  echo str_replace($placeholder, $replace, file_get_contents('error'));
}
?>
