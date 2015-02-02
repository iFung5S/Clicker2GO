<!DOCTYPE html>
<html>
  <head>

  </head>
  <?php
      // define the id of the question to be displayed.
      // Normally it will be passed from a previous page or from session
      $qid = 1;  // Hard coded for now

      // session start or not?
      // connect to mysql
      include_once ('../sqlconnect.php'); // include vs include_once vs require differences

      // get question data
      $result = mysqli_query($conn, "SELECT * FROM `questions` WHERE id=$qid");
      $result_array = mysqli_fetch_assoc($result);
      $question = $result_array["question"];
      $answer1 = $result_array["answer1"];
      // get answers in form and echo
    ?>
  <body>
    <p>
      <?= "$question" ?>
    </p>
    <br/>
    <form>
      <ol>
        <li>
          <input name="answer1" type="radio" value="1"/> <?= "$answer1" ?>
        </li>
      </ol>
    </form>
  </body>
</html>

