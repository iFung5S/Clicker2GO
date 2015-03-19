<?php
  session_start();

  if (!isset($_SESSION['uid'])) {
        header('Location: ../');
  }
  $_SESSION['expiry'] = time() + 1800;
  include ('../lib/dbCon.php');

  if (isset($_POST['ready']) && $_POST['ready'] == 1)
  {
    if (count($_SESSION['type']) > 2 || (count($_SESSION['type']) == 2
            && !in_array(array("type"=>"Administrator"),$_SESSION['type'])))
    {
      if (in_array(array("type"=>"Student"),$_SESSION['type']))
      {  $_SESSION['type'][0]['type'] = "Student (inactive)";
         $_SESSION['type'][1]['type'] = "Lecturer";}
      else if (in_array(array("type"=>"Lecturer"),$_SESSION['type']))
      {  $_SESSION['type'][1]['type'] = "Lecturer (inactive)";
         $_SESSION['type'][0]['type'] = "Student";}

    $type = "";
    foreach ($_SESSION['type'] as $u_type)
      $type = $type.$u_type['type']."<br />";
    $type = $type."<button type='button' class='btn_shadow_animate_orange_small' onClick='switchType();' >Switch type</button>";
    echo $type;
   }
  }
?>
