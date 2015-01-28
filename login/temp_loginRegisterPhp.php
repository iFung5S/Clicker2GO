/*php code for username and password. If need to encode password, add function.
  addition code for register is shown as comment*/
/*dispaly in this file may not right just copy needed content to .php file to use*/
 
//change the code as you want

/* all method below based on post method to same page
   <form method="post" action=
    "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 

   $usernameErr, $passwordErr, $nameErr should add after form element, eg:
       Name: <input type="text" name="name" value="<?php echo $name;?>">
       <span>* <?php echo $nameErr;?></span>
*/
<?php

  // define variables and set to empty values
  $usernameErr = $passwordErr = "";//$nameErr for register
  $username = $password = ""; //$name for register

  //function to simplify input, may not need
  function test_input($data) 
  {
    $data = trim($data); //Strip extra space,tab,newline; or delet this part
    $data = htmlspecialchars($data);
   return $data;
  }
  
   $username = test_input($_POST["username"]);
   $password = $_POST["password"];//or add encoding 
   //$name = test_input($_POST["name"]); //for register

  //check if there any input wrong
  if (empty($_POST["username"])) 
  {
    $usernameErr = "username is required";
  } 
    // check if username only contains letters and numbers,longer than 4 words
  elseif (!preg_match("/^([0-9a-zA-Z]{4,})$/",$username)) 
   {
    $usernameErr = "Wrong username";
   }

  elseif (empty($_POST["password"])) 
  {
    $passwordErr = "password is required";
  } 

  //password length limit, may add longest length, change as want
  elseif (strlen($password)<8)
   {
    $passwordErr = "password should longer than 8 words";
   }


  /*    //name for register
    if (empty($_POST["name"])) 
     {
      $nameErr = "Name is required";
     } 
    // check if name only contains letters
    elseif (!preg_match("/^[a-zA-Z]*$/",$name)) 
     {
     $nameErr = "Only letters allowed";
     }
  */

  else
  {
  /*  encode password 
    function encodePass($data) 
     {
      //function; or use other type
      return $data;
     }
     $enPassword = encodePass($_POST["name"]);
  */

    //code to deal with mysql
    require_once('config.inc.php');

    // Create connection
    $conn = mysqli_connect($database_host, $database_user, $database_pass,
            $group_dbnames);

    // Check connection
    if (!$conn) 
     {
      die("Connection failed: " . mysqli_connect_error());
     }

    $userValidate = "SELECT * FROM user 
    WHERE username='$username'";  

    $search = mysqli_query($conn, $userValidate);
     
    //differentce start here, part only for login
    if (mysqli_num_rows($search)>0)
      {
       $row = mysqli_fetch_assoc($userValidate);
         /*variable enPassword here different as above by default, 
           enPassword above is write in comment. If test without encode,
               then change 'enPassword' to 'password'*/
       if ($enPassword == $row["password"])
         {
          echo "<script>location.href='<name of home page>';</script>"; 
         }
       else
         {
           $passwordErr = "Wrong password";
         }
      }
    else 
     {
       $usernameErr = "Wrong username";
      }

    /*  //for register
     if (mysqli_num_rows($userValidate)>0)
      {
       $usernameErr = "usernamer already exist";
      }
     else
      {
       $sql = "INSERT INTO user (username, password, name) 
       VALUES
       ('$username','$password','$name')";
        //inserted password should be encoded
        //add course if need 
       if (mysqli_query($conn, $sql))
        {
        //jump to the page which states the user created successfully
        //after register user login automatically, so state saved in session
        echo "<script>location.href='<name of page>;</script>"; 
        }
       else
        {
        //this line will appeared at where the php code is.
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
      }
    */

    mysqli_close($conn);
  }
?>
