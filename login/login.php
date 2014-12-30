<!DOCTYPE html>
</html>

<html>
  <head>
    <meta charset="utf-8">
  		<!-- <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'> -->
		<link rel="stylesheet" href="http://www.mrubu.rvhost.com.ar/bootstrap/css/bootstrap.css">
    	<title>My Homepage</title>
    	<link rel="stylesheet" href="style.css">
  </head>


  <body class="homepage">
<div class="form-wrapper">
<br>
  <h1><img src="../images/logo.png" alt="Couldn't load image" style="width:60px; height:60px"> CLICKER2GO </h1>
  <div id=rectangle> </div>

  <br>
  <br>

  <form>
    <div class="form-item">
      <label for="email"></label>
      <input type="text" name="username" autofocus required placeholder="Username" />
    </div>
    <div class="form-item">
      <label for="password"></label>
      <input type="password" name="password" required="required" placeholder="Password" />
    </div>
<p> <?php if(isset($_GET['err']) && $_GET['err'] == 1) { echo "<span class='error'>Incorrect username or password.</span>"; }
                     if(isset($_GET['logout']) && $_GET['logout'] == 1) { echo "<span class='correct'>Logout successfully.</span>"; } ?> </p>
    <div class="button-panel">
      <input type="submit" class="button" title="Login" value="Login" />
    </div>
  </form>
  <div class="form-footer">
    <p><a href="register.php">Register</a></p>
    <p><a href="#">Forgot password?</a></p>
  </div>
</div>

  </body>
</html>
