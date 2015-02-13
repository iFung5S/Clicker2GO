<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Answer Question | Clicker2GO</title>
    <link rel="stylesheet" href="../style.css">

    <?php

      // get qid
      $qid = $_GET['qid'];

      // connect to mysql
      include_once ('../lib/sqlconnect.php');

      $result = mysqli_query($conn, "SELECT * FROM `questions` WHERE id=$qid");
      $result_row = mysqli_fetch_assoc($result);
      $starttime = $result_row["starttime"];
      $countdown = $result_row["countdown"];
      $currenttime = time();

      // check if countdown already started
      if ($starttime == NULL)
        $countdownstarted = false;
      else
      {
        $starttime = strtotime($starttime);
        $countdownstarted = ($currenttime >= $starttime);
      }

      // variables to hold the default submit action and button text
      if ($countdownstarted)
      {
        $postaction = 'resetcountdown';
        $buttontext = "Reset CountDown";
      }
      else
      {
        $postaction = 'startcountdown';
        $buttontext = "Start CountDown";
      }


      // start countdown only if submitted by post
      if (isset($_POST['startcountdown']) && $_POST['startcountdown'] == 1)
      {
        // set the starttime in the database to time=now
        $query = "UPDATE `questions` SET `starttime`=NOW(), `endtime`=NOW()+INTERVAL $countdown SECOND WHERE id=$qid;";
        $update_starttime = mysqli_query($conn, $query);

        // make a new query to get updated time results
        $result = mysqli_query($conn, "SELECT * FROM `questions` WHERE id=$qid");
        $result_row = mysqli_fetch_assoc($result);
        $starttime = strtotime($result_row["starttime"]);
        $countdownstarted = true;
        $buttontext = "Reset CountDown";
        $postaction = 'resetcountdown';
      }

      // reset countdown only if submitted by post
      else if (isset($_POST['resetcountdown']) && $_POST['resetcountdown'] == 1)
      {
        // set the starttime in the database to NULL
        $query = "UPDATE `questions` SET `starttime`=NULL WHERE id=$qid;";
        $update_starttime = mysqli_query($conn, $query);

        // make a new query to get updated time results
        $result = mysqli_query($conn, "SELECT * FROM `questions` WHERE id=$qid");
        $result_row = mysqli_fetch_assoc($result);
        $starttime = strtotime($result_row["starttime"]);
        $countdownstarted = false;
        $buttontext = "Start CountDown";
        $postaction = 'startcountdown';
      }


      // this page url to refresh
      $thispage = "test-countdown.php?qid=$qid"
    ?>

  </head>

  <body class="homepage">
    <p>
      <?php
        echo "current time = $currenttime <br>";
        echo "recorded startime = $starttime <br>";
        if ($countdownstarted)
        {

          echo "Question CountDown already started <br> Click to Reset CountDown";
        }
        else
          echo "Click to start countdown";
      ?>

    </p>
    <br/>
    <form action=<?php echo "$thispage"?> method="POST">
      <input name='<?php echo "$postaction" ?>' type='hidden' value=1 />
      <input type="submit" value='<?php echo "$buttontext" ?>' >
    </form>

  </body>
</html>

