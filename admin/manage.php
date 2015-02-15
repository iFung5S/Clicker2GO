<?php

  // Initialize session
  session_start();

  include ('../lib/dbCon.php');

  $username = $_SESSION['username'];

  $users = ORM::for_table('users')->find_many();

  $table = "";

  if (!empty($users))
  {
    $table = "<table><tr><th>Username</th><th>Name</th><th>Account Type</th><th>Courses</th></tr>";
  }

  $course_list = "";

  foreach ($users as $user)
  {
    $type = ORM::for_table('type')->find_one($user->type);
    $courses = ORM::for_table('user_courses')->where('uid', '$users->id')->find_many();
    foreach ($courses as $course)
    {
      $course_name = ORM::for_table('course_unit')->find_one('$course->id_cu');
      $course_list = $course_list + ", " + $course_name;
    }
    $table = $table + "<tr><td>" + $user->username + "</td><td>" + $user->name + "</td><td>" + $type->type + "</td><td>" + $course_list + "</td></tr>";
  }

  $table = $table + "</table>";

echo $table;

//  $placeholder = array("##name##", "##username##", "##type##", "##course##");
//  $replace = array($users->name, $username, $type->type, $list_course);

//  echo str_replace($placeholder, $replace, file_get_contents('manage'));

?>
