<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Login | Clicker2GO</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body class="homepage">
  <ul>
         <form class="white" action="action.php" method="POST">

            <li> <img class="center" src="logo.png" width="70" height="70" /> CLICKER2GO</li>
            <br>
            Name:
		      <input type="text" name="name" placeholder="Write your username here..." class="name" required />
            E-mail:
		      <input type="text" name="emailaddress" placeholder="Write your password here..." class="email" type="email" required />

            <input type="submit" name="submit" class="btn shadow animate green" value="Login" />
         
         </form>
  </body>
</html>
