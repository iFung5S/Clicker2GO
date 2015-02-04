<!DOCTYPE html>
<html>

<?php

// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: login/login.php');
}
  include_once ('sqlconnect.php');
  $username = $_SESSION['username'];
  $query = "SELECT * FROM user WHERE username='$username'";
  $result = mysqli_query($conn, $query);
?>

  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Home | Clicker2GO</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body class="homepage">
  <h1>HOME</h1>
<script language='JavaScript'> 
	var myDate = new Date(); 
	/* 5:00 am - 11:59 am */
	if ( myDate.getHours() >= 5 && myDate.getHours() < 12 )
	{ 
		document.write('Good morning, '); 
	} 
	else  /* 12:00 nn - 5:59 pm*/
		if ( myDate.getHours() >= 12 && myDate.getHours() < 19 ) 
		{ 
			document.write('Good afternoon, '); 
		} 
		else  /* 6:00 pm - 12:00 mn OR 12:mn - 5:00 am */
			if ( ( myDate.getHours() >= 19 && myDate.getHours() <= 24 ) || ( myDate.getHours() >= 0 && myDate.getHours() < 5 ) )
			{ 
				document.write('Good evening, '); 
			} 
			else  /* the hour is not between 0 and 24, so something is wrong */
			{ 
				document.write('Welcome, '); 
			} 
</script>
<?php echo $_SESSION['name'];?></a>!
</p>
<p><a href="login/logout.php">Logout</a></p>

  <h2>Course</h2>
  <ul>
  <?php
  //list course user have

  $row = mysqli_fetch_assoc($result);
  $course = $row['course'];
  if (empty($course)){
   echo '<li>No course now</>';
  }
  else {
   $course = explode("|",$course);
    for ($i=0;$i<count($course);$i++) {
      $courseName = $course[$i];
      echo "<li><a href='questions/datePage.php?courseName=$courseName'>$courseName</a></li>";    
      }
  }
  ?>
  </ul>

  <form method="POST" action="questions/addCourseTaken.php" >
  <input type="text" name="username" style="visibility:hidden" value="<?php echo $username;?>"/></br>
  <?php
  if($row['type'] == 'student') {
    echo "<select name='courseName' size=5 \>";
    echo "<option value=''>--select course--</option>";

    $dateQuery = "SELECT * FROM questions WHERE (id IN (SELECT min(id) FROM questions GROUP BY course)) ORDER BY course ";
    $course_result = mysqli_query($conn, $dateQuery);
    if (mysqli_num_rows($course_result) != 0) {
    while($row_course = mysqli_fetch_assoc($course_result))
    {
      $courseName = $row_course['course'];
      echo "<option value='$courseName'>$courseName</option>";
    } }
   else {
     echo "<option disabled>NONE</option>";
    } 
  echo "</select></br>"; }
  else {
  echo "<input type='text' name='courseName'/></br>"; } ?>
  <input type="submit" class="button" value="ADD COURSE"/>
  </form>

  </body>
</html>
