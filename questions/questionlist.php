<?php
// Initialize session
session_start();

// Jump to login page if uid not set
if (!isset($_SESSION['uid'])) {
        header('Location: ../');
}

  $uid = $_SESSION['uid'];
  $courseName= $_GET['courseName'];
  $date=$_GET['date'];
  include_once ('../lib/dbCon.php');

  $min_time = implode(' ', array($date,'09:00:00'));

  //check if date format right and avoid access by editing url when not reach the earliest time
  if (!preg_match("/^20\d{2}[\/\-](0?\d|1[0-2])[\/\-]([0-2]?\d|3[01])$/",$date)
      || $_SESSION['type'] == 'student' && time()<strtotime($min_time))
  {
    echo "<script>window.location.assign('datePage.php?courseName=$courseName&err=1');</script>";
  }

  $date=preg_replace('/([\/\-])(0?)(\d)([\/\-])(0?)(\d)$/','-0$3-0$6',$date);
  $cuid = ORM::for_table('course_units')
          ->where('course',$courseName)
          ->find_one()->id;

  $questions_id = ORM::for_table('questions')
                     ->select('id')
                     ->where(array(
                         'id_cu' => $cuid,
                         'date' => $date
                       ))
                     ->find_many();
  $questions_count = $questions_id -> count();

  $questions_list = "";
  $i=1;
  if ($questions_count != 0) {
  foreach ($questions_id as $each_qid) {
    $qid = $each_qid->id;
    $questions_list = $questions_list."<li><a href='question-answer.php?qid=$qid'>Question $i</a></li>";
    $i++;
    }}
  else {
    $questions_list = "<li>No Question</br>(This date page will not be saved if no question added)</li>"; 
  } 
  
  $create_button = "<form method='POST' action='createQuestion.php'>
                    <input type='text' name='courseName' style='visibility:hidden' value='$courseName'/>
                    <input type='text' name='date' style='visibility:hidden' value='$date'/></br>
                    <input type='submit' class='button' value='creat new'/></form>";

  $placeholder = array("##courseName##","##date##","##questions_list##", "##create_question##");
  $replace = array($courseName,$date,$questions_list, "");

  if($_SESSION['type'] != 'Student') {
    $replace = array($courseName,$date,$questions_list,$create_button);
  } 
  echo str_replace($placeholder, $replace, file_get_contents('questionlist'));
?>   

