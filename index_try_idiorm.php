<!DOCTYPE html>
<?php
// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: login/login.php');
}
  include_once ('dbCon.php');
  $username = $_SESSION['username'];

?>
<html>
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

  $user = ORM::for_table('user')
          ->where('username', $username)
          ->find_one();
  $course = $user['course'];
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
  <?php
  if($user['type'] == 'student') {
    echo "<select name='courseName' \>";
    echo "<option value=''>--select course--</option>";
    $course_id = ORM::for_table('questions')
                 ->select('id')
                 ->group_by('course')
                 ->find_many();
    $course = ORM::for_table('questions')
                        ->where_in('id',$course_id)
                        ->order_by_asc('course')
                        ->find_many();
    if (!empty($course)) {
      foreach ($course as $course) {
        $courseName = $course['course']->text;
        echo "<option value='$courseName'>$courseName</option>";
      } 
    }
    else {
      echo "<option disabled>NONE</option>";
    } 
    echo "</select></br>"; 
  }
  else {
    echo "<input type='text' name='courseName'/></br>"; 
  } ?>
  <input type="submit" class="button" value="ADD COURSE"/>
  </form>

  </body>
</html>
