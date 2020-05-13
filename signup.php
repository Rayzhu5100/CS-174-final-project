<?php
require_once 'loginpas.php';


function createFormDoc(){
        return
        <<<_END
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <title>Sign Up</title>
        </head>
        <body>
            <div id="main-container">
              <div class="top-menu">
                  <span class="menu-items"><a href="homepage.php">Home</a></span>
                  <span class="menu-items"><a href="login.php">Login</a></span>
                  <span class="menu-items"><a href="signup.php">Sign Up</a></span>
              </div>
                <h1>Welcome to our page</h1>
                <div id="container">
                    <div id="container-form">
                        <h3 id="container-form-title">Sign Up</h3>
                        <hr>
                        <form method='post' action='signup.php'>
                            <p>Username</p>
                            <input required type="text" name="username">
                            <p>Password</p>
                            <input required type="password" name="password">
                            <p>Confirm Password</p>
                            <input required type="password" name="repassword">
                            <p><a href="login.php">Sign In</a></p>
                            <button type ="submit" name ="register">Sign Up<button/>
                        </form>

        _END;
    }

  function main(){
    global $hn, $un, $pw, $db;
      $conn = new mysqli($hn, $un, $pw, $db);
      if ($conn->connect_error)die("Connection failed!");
      $formDoc = createFormDoc();
      if(isset($_POST['register'])) {
        $username = sanitizeString($_POST['username']);
        $password = sanitizeString($_POST['password']);
        $rePassword = sanitizeString($_POST['repassword']);

        $USERNAME = sanitizeMySQL($conn,$username);
        //check username is unique
        $sql =  $conn->query("SELECT * FROM User WHERE username='$USERNAME'");
        if($sql->num_rows > 0) {
          echo "Username already exists!";
        //check password and repassword match or not
        }else if($password != $rePassword){
          echo "Password doesn't match, please check you input!";
        }else{
          echo "else";

          $salt = generateRandomString();
          $hashed_password = hash('sha256',$salt.sanitizeString($_POST['password']));

          $USERNAME = sanitizeMySQL($conn,$username);
          $PASSWORD = sanitizeMySQL($conn,$hashed_password);
          $SALT = sanitizeMySQL($conn,$salt);
          //insert user data to databse
          $stmt = $conn->prepare("INSERT INTO User(Username,Password,Salt) VALUES (?,?,?)");
          $stmt->bind_param('sss',$USERNAME,$PASSWORD,$SALT);
          $stmt->execute();

          $stmt->close();
        }
      }
    echo $formDoc;
   echo "
             </div>
           </div>
         </div>
         </body>
     </html>
    ";
  }


main();
function sanitizeString($var) {
  $var = stripslashes($var);
  $var = strip_tags($var);
  $var = htmlentities($var);
  return $var;
}

function sanitizeMySQL($connection, $var) {
  $var = $connection->real_escape_string($var);
  $var = sanitizeString($var);
  return $var;
}

// generate random salt
function generateRandomString($length = 10) {
  return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
