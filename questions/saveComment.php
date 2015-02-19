<?php
session_start();

// Jump to login page if uid not set
if (!isset($_SESSION['uid'])) {
        header('Location: ../');
}
  $uid = $_SESSION['uid'];
  include_once ('../lib/dbCon.php');

  $qid = $_POST['qid'];
  $comment = str_replace("\n","<br/>",$_POST['comment']);

  $comments = ORM::for_table('comments')->create();
  $comments->set(array(
             'uid' => $uid,
             'qid' => $qid,
             'comment' => $comment
             ));
  $comments->save();
  echo "<script>window.location.assign('comment.php?qid=$qid');</script>";
?>
