<?php

  session_start();
  
if (!isset($_SESSION['uid'])) {
  header('Location: ../');
  exit(0);
}
else if (time() > $_SESSION['expiry'])
{
  session_unset();
  header('Location: ../login/login.php?TIMEOUT');
  exit(0);
} else {
  $_SESSION['expiry'] = time() + 1800;
}

  if (!in_array(array("type"=>"Administrator"),$_SESSION['type']))
  {
    echo "<script>window.alert('You do not have permission for this operation.');
    window.location.assign('../');</script>";
    exit(0);
  }
  
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
