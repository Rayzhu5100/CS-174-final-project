<?php
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error)die("Connection failed!");

$login_status;

/*database:
CREATE TABLE User(
Username varchar(30) PRIMARY KEY,
Email varchar(30),
Password binary(255),
Salt  varchar(8)
);
*/

//create account
if(isset($_POST['register'])) {
  $username = sanitizeString($_POST['username']);
  $email = sanitizeString($_POST['email']);
  $password = sanitizeString($_POST['password']);
  $rePassword = sanitizeString($_POST['repassword']);

  $USERNAME = sanitizeMySQL($conn,$username);
  //check username is unique
  $sql = $conn->query("SELECT * FROM User WHERE username='$USERNAME'");
  if($sql->num_rows > 0) {
    echo "Email already exists!"
  //check password and repassword match or not
  }else if($password != $rePassword){
    echo "Password doesn't match, please check you input!";
  }else{
    $salt_length = 8;
    //generate a random salt value
    $salt = mcrypt_create_iv($salt_length, MCRYPT_DEV_URANDOM);
    $hashed_password = hash('sha256',$salt.sanitizeString($_POST['password']));

    $USERNAME = sanitizeMySQL($conn,$username);
    $EMAIL = sanitizeMySQL($conn,$email);
    $PASSWORD = sanitizeMySQL($conn,$hashed_password);
    $SALT = sanitizeMySQL($conn,$salt);
    //insert user data to databse
    $stmt = $conn->prepare("INSERT INTO User VALUES (?,?,?,?)");
    $stmt->bind_param('ssss',$USERNAME,$EMAIL,$PASSWORD,$SALT);
    $stmt->execute();
    $result->close();
    $stmt->close();
  }
}

//login
if(isset($_POST['login'])) {
  $username = sanitizeString($_POST['username']);
  $password = sanitizeString($_POST['password']);


  $USERNAME=sanitizeMySQL($conn,$username);
  //use username find salt, if no result, the username doesn't exist
  $salt = "SELECT salt FROM User WHERE Username='".$username."'";
  $result=mysqli_query($conn,$salt);
  if(!$result){
    die("Username doesn't exist!!");
  }else{
    $row=mysqli_fetch_assoc($result);
    $hashed_password = hash('sha256',$row['salt'].sanitizeString($_POST['password']));
    $pw=sanitizeMySQL($conn,$hashed_password);
    $sql = $conn->query("SELECT * FROM admin WHERE username='$username' AND password='$hashed_password'");

    if($sql->num_rows == 1) {
      $login_status = 1;
    }
  }
}

if(isLogin($login_status) == true){
  // more to add
}






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
