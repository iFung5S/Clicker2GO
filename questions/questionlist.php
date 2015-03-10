<?php
// Initialize session
session_start();

// Jump to login page if uid not set
if (!isset($_SESSION['uid'])) {
        header('Location: ../');
}

if(isset($_GET['courseName'])){

 if(isset($_GET['date'])){

  $uid = $_SESSION['uid'];
  $courseName= $_GET['courseName'];
  $date=$_GET['date'];
  include_once ('../lib/dbCon.php');

  $min_time = implode(' ', array($date,'09:00:00'));

  //check if date format right and avoid access by editing url when not reach the earliest time
  if (!preg_match("/^20\d{2}[\/\-](0?\d|1[0-2])[\/\-]([0-2]?\d|3[01])$/",$date)
      || $_SESSION['type'] == 'Student' && time()<strtotime($min_time))
  {
    echo "<script>window.location.assign('datePage.php?courseName=$courseName&err=1');</script>";
  }

  $date = preg_replace('/([\/\-])(0?)(\d)([\/\-])(0?)(\d)$/','-0$3-0$6',$date);
  $cuid = ORM::for_table('course_units')
        ->where('course',$courseName)
        ->find_one();

 if (!empty($cuid)) {

  $cuid = $cuid->id;

  $questions_id = ORM::for_table('questions')
                     ->select('id')
                     ->where(array(
                         'id_cu' => $cuid,
                         'date' => $date
                       ))
                     ->find_many();

  $questions_count = $questions_id -> count();

  $errorCheck = false;
  $questions_list = "";
  $i=1;
  if ($questions_count != 0) {
  foreach ($questions_id as $each_qid) {
    $qid = $each_qid->id;
    if ($_SESSION['type'] == 'Student'){
    $questions_list = $questions_list."<div style='margin-bottom:0'><a href='question-answer.php?seq=$i&amp;qid=$qid'>Question $i</a></div>
    <div class=rectangle> </div>";}
    else {
     $confirm = "javascript:if(confirm('Do you want to remove this question $i? (Rest questions will be re-rodered)'))location='removeQuestion.php?qid=$qid'";

    $questions_list = $questions_list."<div style='margin-bottom:0'><a href='question-lecture.php?seq=$i&amp;qid=$qid'>Question $i</a> <span class='redCross'><a href='#' style='' onclick=\"$confirm\">x</a></div><div class=rectangle></span></div>";
    }
    $i++;
    }}
  else {
    //error for student if date not exist
    if ($_SESSION['type'] == 'Student') {
      $errorCheck = true;
    }

    $questions_list = "<div>No Questions.<br/>(This page will not be saved if no question are added.)</div>";
  }

  $create_button = "<form method='GET' action='createQuestion.php'>
                    <input type='text' name='courseName' style='display:none' value='$courseName '/>
                    <input type='text' name='date' style='display:none' value='$date'/>
                    <div class='button-panel'><input type='submit' class='button' value='Create New Question'/></div></form>";

  $placeholder = array("##courseName##","##date##","##date_long##","##questions_list##", "##create_question##","##name##");
  $replace = array($courseName,$date,date("d M Y",strtotime($date)),$questions_list, "",$_SESSION['name']);

  if($_SESSION['type'] != 'Student') {
    $replace = array($courseName,$date,$questions_list,$create_button,$_SESSION['name']);
  }
  if ($errorCheck) {
    $information = "The date '" . date("d M Y",strtotime($date)) . "' is not exist for '$courseName'.";
    $placeholder = array("##information##","##name##");
    $replace = array($information,$_SESSION['name']);
    echo str_replace($placeholder, $replace, file_get_contents('error'));
  }
  else {
    echo str_replace($placeholder, $replace, file_get_contents('questionlist'));
  }

//for errors
}
 else {
  $information = "The course '$courseName' is not exist.";
  $placeholder = array("##information##","##name##");
  $replace = array($information,$_SESSION['name']);
  echo str_replace($placeholder, $replace, file_get_contents('error'));
  }
 }
 else {
  $information = "The date is empty.";
  $placeholder = array("##information##","##name##");
  $replace = array($information,$_SESSION['name']);
  echo str_replace($placeholder, $replace, file_get_contents('error'));
 }
}
else {
  $information = "The course name is empty.";
  $placeholder = array("##information##","##name##");
  $replace = array($information,$_SESSION['name']);
  echo str_replace($placeholder, $replace, file_get_contents('error'));
}
?>
