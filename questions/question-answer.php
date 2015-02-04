<!DOCTYPE html>
<html>
  <head>

  </head>
  <?php
      // define the id of the question to be displayed.
      // Normally it will be passed from a previous page or from session
      $qid = 1;  // Hard coded for now

      // session start or not? add session after code completely working
      // connect to mysql
      include_once ('../sqlconnect.php'); // include vs include_once vs require differences

      // get question data
      $result = mysqli_query($conn, "SELECT * FROM `questions` WHERE id=$qid");
      $result_row = mysqli_fetch_assoc($result);
      $question = $result_row["question"]; // question is column with index 3

      $qid_unused = $_GET['qid'];//qid get from question list page,unused as code not completely working
      
      // get answers in form and echo
   ?>
   
  <body>
    <p>
      <?= "$question" ?>
    </p>
    <br/>
    <form>
      <ol>
        <?php
        // use index j for the number of answer
        for ($i=1; $i<=6; $i++) // answers are from index 4 to 9 
        {
          if (!empty($result_row['answer' . $i])) // only print if value not NULL
          { 
            $j=$result_row['answer' . $i];
            echo "<li>
                    <input name='answer' type='radio' value=$i /> $j
                  </li>";
          }
        }
        ?>
      </ol>
    </form>
  </body>
</html>


