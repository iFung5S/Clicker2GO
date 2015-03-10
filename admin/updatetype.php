<?php

  session_start();

  include_once ('../lib/dbCon.php');

  $username = $_SESSION['uid'];
  $redirect = "";

  $user = $_POST['user'];
  $users = ORM::for_table('users')->where('username',$user)->find_one();

  $old_entries = ORM::for_table('u_type')->where('uid',$users->id)->find_many();
  foreach ($old_entries as $old_entry)
    $old_entry->delete();

  foreach ($_POST['types'] as $type)
  {
    $update = ORM::for_table('u_type')->create();
    $update->set(array(
             'uid'=>$users->id,
             'id_t'=>$type,
             ));
    $update->save();
  }

    $redirect = "<script>window.location.assign('manage.php');</script>";

  echo str_replace("##redirect##", $redirect, file_get_contents('page_only_title'));

?>
