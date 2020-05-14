<?php
session_start();
require_once 'db_info.php';

/*database:
CREATE TABLE User(
id int auto_increment primary key,
Username varchar(30) unique,
Password varchar(255),
Salt  text
);
*/
function createFormDoc()
{
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
                        <h3 id="container-form-title">Login</h3>
                        <hr>
                        <form method='post' action='login.php'>
                            <p>Username</p>
                            <input required type="text" name="username">
                            <p>Password</p>
                            <input required type="password" name="password">
                            <p><a href="post.php">Forget password???</a></p>
                            <button name ="Login">Login</button>
                        </form>
                        
        _END;
}

function main()
{
  global $hn, $un, $pw, $db;
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die("Connection failed!");
  $formDoc = createFormDoc();
  //login
  $error = "";
  if (isset($_POST['Login'])) {
    $username = sanitizeMySQL($conn, sanitizeString($_POST['username']));
    //use username find salt, if no result, the username doesn't exist
    $salt = "SELECT Salt FROM User WHERE Username='" . $username . "'";
    $result = mysqli_query($conn, $salt);
    if (!$result) die("Something Wrong!");

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $password = sanitizeMySQL($conn, hash('sha256', $row['Salt'] . sanitizeString($_POST['password'])));
      $sql = "SELECT * FROM User WHERE Username='$username'";
      $q = mysqli_query($conn, $sql);
      $info = mysqli_fetch_assoc($q);
      $db_password = $info["Password"];
      
      if(strcmp($password, $db_password) == 0){    
        $_SESSION['id'] = $info['id'];
        header('Location: addpost.php');        
      }else{
        $error = "<span class='error'>Username/Password are wrong!!!</span>";
      }
    } else $error = "<span class='error'>Username/Password are wrong!!!</span>";
  }
  echo $formDoc;
  echo $error;
  echo "          
              </div>
            </div>
          </div>
        </body>
    </html>
   ";
}

function sanitizeString($var)
{
  $var = stripslashes($var);
  $var = strip_tags($var);
  $var = htmlentities($var);
  return $var;
}

function sanitizeMySQL($connection, $var)
{
  $var = $connection->real_escape_string($var);
  $var = sanitizeString($var);
  return $var;
}

main();
