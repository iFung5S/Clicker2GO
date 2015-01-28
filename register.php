<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>Register | Clicker2GO</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body class="homepage">
  <ul>
         <form class="white" action="login_check.php" method="POST">
            <li> <font size=14>Register</font></li>
		      <input type="text" name="username" id="pw1" placeholder="Username" class="name" required />
		      <input type="password" name="password" id="pw2" placeholder="Password" onkeyup="validate()" class="email" required />
		      <input type="password" name="repeat password" placeholder="Password" class="email" required /><span id="check"></span>
		      <input type="text" name="name" placeholder="name" class="email" required />

            <input type="submit" name="submit" class="btn shadow animate green" value="register" />
         
         </form>
 <script>
          function validate() {
              var pw1 = document.getElementById("pw1").value;
              var pw2 = document.getElementById("pw2").value;
              if(pw1 == pw2) {
                  document.getElementById("check").innerHTML="<font color='green'>same input</font>";
                  document.getElementById("submit").disabled = false;
              }
              else {
                  document.getElementById("check").innerHTML="<font color='red'>different inpur</font>";
                document.getElementById("submit").disabled = true;
              }
          }
      </script>
  </body>
</html>
