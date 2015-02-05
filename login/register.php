<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Register | Clicker2GO</title>
    <link rel="stylesheet" href="../style.css">
  </head>

  <body class="homepage">
  <ul>
         <form class="white" action="register_check.php" method="POST">
            <li> <font size=10>Register</font></li>
            <p><a href='login.php'>Back to login</a></p></br>
		      <input type="text" name="name" placeholder="Name" id="name" class="name" onKeyUp="validate();"required /></br>
		      <input type="text" name="username" placeholder="Username" id="username" class="name" onKeyUp="validate();" required /></br>
		      <input type="password" name="password" id="password1" placeholder="Password" class="email" onKeyUp="validate();" required /></br>
		      <input type="password" name="password2" id="password2" placeholder="Re-type Password" class="email" onKeyUp="validate();" required />
            <p id="check" class="error"><?php if(isset($_GET['exist']) && $_GET['exist'] == 1) echo "Username already exist."; ?></p>
            <input type="submit" name="submit" id="submit" class="btn shadow animate grey" value="Register" disabled />

         </form>
 <script>
          function validate() {
              var name = document.getElementById("name").value != "";
              var username = document.getElementById("username").value != "";
              var pw1 = document.getElementById("password1").value;
              var pw2 = document.getElementById("password2").value;
              var password = pw1 != "";
              if(pw1 != pw2) {
                document.getElementById("check").innerHTML="Passwords do not match!";
                document.getElementById("submit").disabled = true;
                document.getElementById("submit").className="btn shadow animate grey";
              }
              else {
                if(name && username && password) {
		  document.getElementById("check").innerHTML="";
                  document.getElementById("submit").disabled = false;
                  document.getElementById("submit").className="btn shadow animate green";
                }
                else {
                  document.getElementById("submit").className="btn shadow animate grey";
                }
             }
          }
      </script>
  </body>
</html>