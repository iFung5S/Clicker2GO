<?php
session_start();

// Jump to login page if uid not set
if (!isset($_SESSION['uid'])) {
        header('Location: ../');
}
  $_SESSION['expiry'] = time() + 1800;
  
  $uid = $_SESSION['uid'];
  include_once ('../lib/dbCon.php');

  $qid = $_POST['qid'];
 if (!preg_match("/^\s*$/",$_POST['comment'])) {
  $comments = ORM::for_table('comments')->create();
  $comments->set(array(
             'uid' => $uid,
             'qid' => $qid,
             'comment' => $_POST['comment']
             ));
  $comments->save();
 }

?>
