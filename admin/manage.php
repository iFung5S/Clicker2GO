<?php

  // Initialize session
  session_start();

  include ('../lib/dbCon.php');

  // Redirect if not administrator
  if ($_SESSION['type'] != 'Administrator')
    echo "<script>window.alert('You do not have permission to view.'); window.location.assign('../');</script>";

  $username = $_SESSION['uid'];

  $users = ORM::for_table('users')->find_many();

  $table = "";

  if (!empty($users))
  {
    $table = $table . "<table border='1' width='100%'>";
    $table = $table . "<tr><th>Username</th><th>Name</th><th>Account Type</th><th>Courses</th></tr>";
  }

  $type_list = "";
  $course_list = "";

  foreach ($users as $user)
  {
    $table = $table . "<tr>";
    $table = $table . "<td>" . $user->username . "</td>";
    $table = $table . "<td>" . $user->name . "</td>";

    $types = ORM::for_table('u_type')->where('uid', $user->id)->find_many();
    $i = 1;
    foreach ($types as $type)
    {
      $type_name = ORM::for_table('type')->find_one($type->id_t);
      if ($i != 1)
        $type_list = $type_list . ", ";
      $type_list = $type_list . $type_name->type;
      $i++;
    }
    $table = $table . "<td>" . $type_list . "</td>";
    $type_list = "";

    $courses = ORM::for_table('user_courses')->where('uid', $user->id)->find_many();
    $i = 1;
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

  $placeholder = array("##table##");
  $replace = array($table);

  echo str_replace($placeholder, $replace, file_get_contents('manage'));

?>
