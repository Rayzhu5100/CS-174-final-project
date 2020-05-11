<?php
require_once 'loginpas.php';


/*database:
CREATE TABLE User(
Username varchar(30) PRIMARY KEY,
Password binary(255),
Salt  text
);
*/
function createFormDoc(){
        return
        <<<_END
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="login.css">
            <title>Sign Up</title>
        </head>
        <body>
            <div id="main-container">
                <h1>Welcome to our page</h1>
                <div id="container">
                    <div id="container-form">
                        <h3 id="container-form-title">Login</h3>
                        <hr>
                        <form method='post' action='login.php'>
                            <p>Username</p>
                            <input type="text" name="username">
                            <p>Password</p>
                            <input type="password" name="password">
                            <p><a href="#">Forget password???</a></p>
                            <button type ="submit" name ="Login">Login</button>
                        </form>
        _END;
    }

 function main(){
   global $hn, $un, $pw, $db;
   $conn = new mysqli($hn, $un, $pw, $db);
   if ($conn->connect_error)die("Connection failed!");
   $formDoc = createFormDoc();
   //login
   if(isset($_POST['Login'])) {
     $username = sanitizeMySQL($conn,sanitizeString($_POST['username']));
     //use username find salt, if no result, the username doesn't exist
     $salt = "SELECT Salt FROM User WHERE Username='".$username."'";
     $result = mysqli_query($conn, $salt);
     if(!$result) die("Something Wrong!");
     if(mysqli_num_rows($result) > 0){
       $row=mysqli_fetch_assoc($result);
       $password=sanitizeMySQL($conn,hash('sha256',$row['Salt'].sanitizeString($_POST['password'])));
       $sql = "SELECT * FROM User WHERE Username='$username' AND Password='$password'";

       if($result = mysqli_query($conn, $sql)){
         if(mysqli_num_rows($result) > 0){
           echo "login successful!<br>";
           while($row = mysqli_fetch_array($result)){
           echo $row['Id'];
          }
        }else{
          echo "Username and password doesn't match!";
        }
      }
    }else echo "Username doesn't exist!";
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
//create account
// if(isset($_POST['register'])) {
//   $username = sanitizeString($_POST['username']);
//   $email = sanitizeString($_POST['email']);
//   $password = sanitizeString($_POST['password']);
//   $rePassword = sanitizeString($_POST['repassword']);
//
//   $USERNAME = sanitizeMySQL($conn,$username);
//   //check username is unique
//   $sql = $conn->query("SELECT * FROM User WHERE username='$USERNAME'");
//   if($sql->num_rows > 0) {
//     echo "Email already exists!"
//   //check password and repassword match or not
//   }else if($password != $rePassword){
//     echo "Password doesn't match, please check you input!";
//   }else{
//     $salt_length = 8;
//     //generate a random salt value
//     $salt = mcrypt_create_iv($salt_length, MCRYPT_DEV_URANDOM);
//     $hashed_password = hash('sha256',$salt.sanitizeString($_POST['password']));
//
//     $USERNAME = sanitizeMySQL($conn,$username);
//     $EMAIL = sanitizeMySQL($conn,$email);
//     $PASSWORD = sanitizeMySQL($conn,$hashed_password);
//     $SALT = sanitizeMySQL($conn,$salt);
//     //insert user data to databse
//     $stmt = $conn->prepare("INSERT INTO User VALUES (?,?,?,?)");
//     $stmt->bind_param('ssss',$USERNAME,$EMAIL,$PASSWORD,$SALT);
//     $stmt->execute();
//     $result->close();//ss
//     $stmt->close();
//   }
// }
//
//
//
// if(isLogin($login_status) == true){
//   // more to add
// }



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

function isLogin($n){
  if($n == 1) return true;
  else return false;
}
