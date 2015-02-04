<!DOCTYPE html>
<?php
// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: ../login/login.php');
}

 $courseName = $_POST['courseName'];
 $date = $_POST['date'];
  include_once ('../sqlconnect.php');
?>
<html>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Create Question | Clicker2GO</title>
    <link rel="stylesheet" href="../style.css">
  </head>

  <body class="homepage">
      <div>
      <span><a href='../index.php'>Home</a></span>>
      <span><a href="datePage.php?courseName=<?php echo $courseName; ?>"><?php echo $courseName; ?></a></span>>
      <span><a href="questionlist.php?date=<?php echo $date; ?>&courseName=<?php echo $courseName; ?>"><?php echo $date; ?></a></span>>
      <span>Create Question</span></div>

    <form method="POST" action="saveQuestion.php">
      <div>Question:</div>
      <textarea rows="5" cols="50" name="question" autofocus required></textarea></br>
      <div>answer 1:</div>
      <input type="text" name="answer1" size="50" autofocus required>
      <input type="checkbox" name="correct[]" value="answer1" id="answer1"/>
      <label for="answer1">correct</label></br>
      <div>answer 2:</div>
      <input type="text" name="answer2" size="50" autofocus required>
      <input type="checkbox" name="correct[]" value="answer2" id="answer2"/>
      <label for="answer2">correct</label></br>
      <div>answer 3:</div>
      <input type="text" name="answer3" size="50">
      <input type="checkbox" name="correct[]" value="answer3" id="answer3"/>
      <label for="answer3">correct</label></br>
      <div>answer 4:</div>
      <input type="text" name="answer4" size="50">
      <input type="checkbox" name="correct[]" value="answer4" id="answer4"/>
      <label for="answer4">correct</label></br>
      <div>answer 5:</div>
      <input type="text" name="answer5" size="50">
      <input type="checkbox" name="correct[]" value="answer5" id="answer5"/>
      <label for="answer5">correct</label></br>
      <div>answer 6:</div>
      <input type="text" name="answer6" size="50">
      <input type="checkbox" name="correct[]" value="answer6" id="answer6"/>
      <label for="answer6">correct</label></br>
      <input type="submit" class="button" value="CREATE"/> 
      <input type="text" name="courseName" style="visibility:hidden" value="<?php echo $courseName;?>"/>
      <input type="text" name="date" style="visibility:hidden" value="<?php echo $date;?>"/></br>
    </form>

  </body>
</html>
