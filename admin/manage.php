<?php

  // Initialize session
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

  include ('../lib/dbCon.php');

  // Redirect if not administrator
  if (!in_array(array("type"=>"Administrator"),$_SESSION['type']))
  {
    echo "<script>window.alert('You do not have permission to view.'); window.location.assign('../');</script>";
  exit(0);
  }

  $username = $_SESSION['uid'];

  $users = ORM::for_table('users')->find_many();

  $table = "";

  $edit ="";
  if (isset($_GET['edit']))
    $edit = $_GET['edit'] == 1;

  if (!empty($users))
  {
    $table = $table . "<table border='1' class='chg_p'>";
    if ($edit)
      $button = "<form action='?' method='post'><input type='submit' class='btn_shadow_animate_orange' value='Cancel' /></form>";
    else
      $button = "<form action='?edit=1' method='post'><input type='submit' class='btn_shadow_animate_orange' value='Edit' /></form>";
    $table = $table . "<tr><th>Username</th><th>Name</th><th style='width:190px;'>Account Type<br />" . $button . "</th><th>Courses</th></tr>";
  }

  $type_list = "";
  $course_list = "";

  if (!$edit)
    $disable = "disabled";
  else
    $disable = "";

  foreach ($users as $user)
  {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $user->username . "</td>";
    $table = $table . "<td>" . $user->name . "</td>";

    $list_types = ORM::for_table('type')->find_many();
    foreach ($list_types as $list_type)
    {
      $types = ORM::for_table('u_type')->where('uid', $user->id)->find_many();
      $check = "";
      foreach ($types as $type)
        if ($type->id_t == $list_type->id)
          $check = "checked='checked'";
      $type_list = $type_list . "<input type='checkbox' name='types[]' value='$list_type->id' $check $disable onChange='this.form.submit();'> " . $list_type->type . "<br />";
    }

    $table = $table . "<td><form action='updatetype.php' method='post'><input type='hidden' name='user' value='$user->username' />" . $type_list . "</form></td>";
    $type_list = "";

    $courses = ORM::for_table('user_courses')->where('uid', $user->id)->find_many();
    $i = 1;
    $course_list = " ";
    foreach ($courses as $course)
    {
      $course_name = ORM::for_table('course_units')->where('id', $course->id_cu)->find_one();
      if ($i != 1)
        $course_list = $course_list . ", ";
      $course_list = $course_list . $course_name->course;
      $i++;
    }
    $table = $table . "<td>" . $course_list . "</td>";
    $course_list = "";

    $table = $table . "</tr>";
  }

  $table = $table . "</table>";

  $placeholder = array("##table##","##name##");
  $replace = array($table,$_SESSION['name']);

  echo str_replace($placeholder, $replace, file_get_contents('manage'));

?>
