<?php

  // Initialize session
  session_start();

  // Jump to login page if uid not set
  if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit(0);
  }
  else if (time() > $_SESSION['expiry'])
  {
    session_unset();
    header('Location: login.php?TIMEOUT');
    exit(0);
  } else
    $_SESSION['expiry'] = time() + 1800;

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
  $type = "";
  foreach ($_SESSION['type'] as $u_type)
    $type = $type.$u_type['type']."<br />";

  if (count($_SESSION['type']) > 2 || (count($_SESSION['type']) == 2
          && !in_array(array("type"=>"Administrator"),$_SESSION['type'])))
  {
    $type = $type."<button type='button' class='btn_shadow_animate_orange_small' onClick='switchType();'>Switch Type</button>";
    $switch_js = "<script src='//code.jquery.com/jquery-1.11.2.min.js'></script>
                  <script type='text/javascript'>
                    function switchType() {
                      $.post('switch_type.php',{ready:'1'},function(data){
                        $('#type').html(data);
                      });
                    }
                    </script>";
  } else { $switch_js = ""; }
  $placeholder = array("##name##", "##username##", "##type##", "##course##","##js##");
  $replace = array($user->name, $username, $type, $list_course, $switch_js);

  echo str_replace($placeholder, $replace, file_get_contents('profile'));

?>
