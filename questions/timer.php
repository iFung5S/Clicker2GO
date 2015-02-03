<?php

session_start();

include('../sqlconnect.php');

$id = 1; // Just hardcoded 1 for now, should get from embedding page

$query = "SELECT starttime, endtime FROM questions WHERE id='$id'";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
if ($row['starttime'] != NULL)
{
  $time = time();
  $endtime = strtotime($row['endtime']);

  $countdown = $endtime - $time;
  echo "<body onLoad='timer();'>"; // Starts timer on load
  echo "<span id='timer'></span>";
  echo "
    <script>
      var count = $countdown; // Comes from PHP
      var counter = setInterval(timer, 1000);
      var message = '';
      function timer()
      {
        count -= 1;
        if (count < 0)
        {
        clearInterval(counter);
        message = 'Ended.';
        document.getElementById('timer').innerHTML = message;
        }
        else
        {
          var seconds = count % 60;
          var minutes = Math.floor(count / 60);
          var hours = Math.floor(minutes / 60);
          minutes %= 60;
          hours %= 60;
          if (seconds < 10) // Add leading zero if seconds is 1 digit
            seconds = '0' + seconds;
          if (minutes < 10) // Add leading zero if minutes is 1 digit
            minutes = '0' + minutes;
          message = minutes + ':' + seconds;
          if (hours > 0)
          {
            if (hours < 10) // Add leading zero if hours is 1 digit but not 0
              hours = '0' + hours;
            message = hours + ':' + message; // Print hours only if > 0
          }
        }
      document.getElementById('timer').innerHTML = message;
      }
    </script>
  ";

}
else
{
  echo "Not started.";
}
?>
