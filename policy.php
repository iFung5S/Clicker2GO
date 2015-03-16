<?php
// Initialize session
session_start();

// if user logged in get the user name
if (!isset($_SESSION['uid'])) {

  $name = '';
  $logbutton = "<button type='button' class='logoutButton' onclick='window.location.assign(\"login/login.php\")'>Login</button>";

}
else {

  include_once ('lib/dbCon.php');
  $uid = $_SESSION['uid'];
  $name =  $_SESSION['name'];
  $logbutton = "<button type='button' class='logoutButton' onclick='window.location.assign(\"login/logout.php\")'>Logout</button>";

}

$placeholder = array("##name##", "##logbutton##");
$replace = array($name, $logbutton);

echo str_replace($placeholder, $replace, file_get_contents('policy'));

?>
