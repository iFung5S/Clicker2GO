<?php

  // Initialize session
  session_start();

  // Jump to login page if uid not set
  if (!isset($_SESSION['uid'])) {
        header('Location: ../');
  }

  include ('../lib/dbCon.php');

  $uid = $_SESSION['uid'];
  $user = ORM::for_table('users')->find_one($uid);
  $username = $user->username;

  // $course = $users->course;
  $list_course = "";
  $courses = ORM::for_table('course_units')
             ->select('course_units.course')
             ->join('user_courses',array(
                    'course_units.id','=','user_courses.id_cu'))
             ->where('user_courses.uid',$uid)
             ->order_by_asc('course_units.course')
             ->find_many();

  $exist = $courses->count();
  if ($exist == 0){
   $list_course = 'No Course';
  }
  else {
   foreach ($courses as $course) {
     $courseName = $course->course;
     $list_course = $list_course . "<a href='../questions/datePage.php?courseName=$courseName'>$courseName</a><br />";
     }
  }

  $type = $_SESSION['type'];

  $placeholder = array("##name##", "##username##", "##type##", "##course##");
  $replace = array($user->name, $username, $type, $list_course);

  echo str_replace($placeholder, $replace, file_get_contents('profile'));

?>
