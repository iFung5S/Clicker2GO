<!DOCTYPE html>
<html>

<?php

// Initialize session
session_start();

// Jump to login page if username not set
if (!isset($_SESSION['username'])) {
        header('Location: login/login.php');
}

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
  </body>
</html>
