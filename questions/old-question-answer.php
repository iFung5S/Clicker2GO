<!DOCTYPE html>
<html>
  <head>

  </head>
  <?php
      // define the id of the question to be displayed.
      // Normally it will be passed from a previous page or from session
      $qid = $_GET['qid'];//qid get from question list page
      //$qid = 1;  // Hard coded for now

      // session start or not? add session after code completely working
      // connect to mysql
      include_once ('../lib/sqlconnect.php'); // include vs include_once vs require differences

      // get question data
      $result = mysqli_query($conn, "SELECT * FROM `questions` WHERE id=$qid");
      $result_row = mysqli_fetch_assoc($result);
      $question = $result_row["question"];
      // when the lecturer presses the start button starttime is set to current time on the server
      // otherwise is NULL
      $starttime = $result_row["starttime"];
      $countdown = 30; // hardcoded for now until i change database structure
      //$countdown = $result_row["countdown"]; // in seconds

   ?>

  <body>
    <p>
      <?php
      if ($starttime == NULL)
        // for now use qid. To implement:it should return the question number within the lecture
        echo "Question $qid";
      else
        echo "$question";
      ?>
    </p>
    <br/>
    <form>
      <ol type = A>  <!-- type A, B, C.. list -->
        <?php
        // use index i for the answer number
        for ($i=1; $i<=6; $i++) // 6 are the maximum allowed answers
        {
          $answer=$result_row['answer' . $i]; // get the specific answer
          if (!empty($answer)) // only print the answer if it exists (its value not NULL)
          {
            // if start button not pressed by lecturer do not show the actual questions
            // only print the A, B, C... placeholders
            if ($starttime == NULL)
              $answer = "";
            echo "<li>
                    <input name='answer' type='radio' value=$i /> $answer
                  </li>";
          }
        }
        ?>
      </ol>
    </form>
  </body>
</html>

<!-- store qid
get qid row as assoc array
#need for hidden col in questions table (when question created ask options when to unhide: when lecture #starts, when timer starts, always visible)
if $hidden = true
  if lecture has not started (date col can become datetime - when creating dates-lecturers give also time)
    report: the question will be revealed in ..  when the lecture starts
  if javascript available
    start a countdown with javascript and refresh at 0
  else
    draw a button to refresh in ... minutes
else (if not hidden)
  print question
  loop to print answers in forms
  start countdown (get value defined by lecturer + column countdown)

  submit button(always)
    redirect and show answers
      parse the page in the same way but visualize ?: add a red x or green tick?
      show statistics
    (caching on the server to deliver static results?)

    default question starts when lecturer presses button
      do not load question if user not logged in and chosen course // don't implement now, for later
      do not load question if start_time = NULL
      this page is loaded -> means that the button has already been pressed by the lecturer
      (alternative implementation -> show 1,2,3 or A, B, C but not the question itself. i.e mimic a clicker
      if you press the answer and it is recorded in the correct time interval the answer gets registered)
DATABASE = use start_time (Null before lecture presses button)
         = use countdown value instead of end_time

      always show submit button (multiple submit press not allowed)
        if the submit button is not pressed when timeout then the last input value gets recorded
      record answer
        change the value in answer-qid-user-correct answer
        record also timestamp for answer (useful for statistics later maybe)
DATABASE = for now correct answer(s) as text field(efficiency?, enum)
        redirect to other page (question-answered.php?) empty for now only after time elapsed
        // afterwards use caching for performance ( many calls at the same time)
        only send the the correct answer's to the second page



-->


