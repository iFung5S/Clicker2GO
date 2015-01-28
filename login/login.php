<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Login | Clicker2GO</title>
    <link rel="stylesheet" href="../style.css">
  </head>

  <body class="homepage">
  <ul>
         <form class="white" action="login_check.php" method="POST">

            <li> <img class="center" src="images/logo.png" width="70" height="70" /> CLICKER2GO</li>
            <br />
		      <input type="text" name="username" id="username" placeholder="Username" class="name" required />
		      <input type="password" name="password" placeholder="Password" class="email" required />
           <p> <?php if($_GET['err'] == 1) {echo "<span class='error'>Incorrect username or password.</span>";}
                     if($_GET['logout'] == 1) {echo "<span class='correct'>Logout successfully.</span>";} ?> </p>
            <input type="submit" name="submit" class="btn shadow animate green" value="Login" />
         
         </form>
    <script>
      document.getElementById('username').select();
    </script>
  </body>
</html>
