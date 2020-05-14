
<?php
require_once 'db_info.php';
session_start();
function createFormDoc()
{
  return
    <<<_END
        <!DOCTYPE html>
        <html lang="en">
        
        <head>
          <meta charset="UTF-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <link rel="stylesheet" href="style.css" />
          <title>HomePage</title>
        </head>
        
        <body>
          <div id="page-container">
            <div class="top-menu">    
        _END;
}

function htmlMenu()
{
  $htmlContent = "";

  if (!isset($_SESSION['id']) && empty($_SESSION['id'])) {
    $htmlContent .= '
        <span class="menu-items"><a href="login.php">Login</a></span>
        <span class="menu-items"><a href="signup.php">Sign Up</a></span>     
    ';
  } else {
    $htmlContent .= '
    <span class="menu-items"><a href="addpost.php">New Post</a></span>     
    <span class="menu-items"><a href="logout.php">Logout</a></span>';
  }
  $htmlContent .= '
      </div>      
      <div id="page-post">     
  ';
  return $htmlContent;
}

function main()
{
  global $hn, $un, $pw, $db;
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die("Connection failed!");
  echo createFormDoc();
  $htmlContent = htmlMenu();

  $id = $_GET['postid'];
  $ID = sanitizeMySQL($conn, $id); //something wrong with this line

  $query = "SELECT title,text,image FROM Post WHERE id='" . $ID . "'";
  $q = mysqli_query($conn, $query);
  $post = mysqli_fetch_assoc($q);
  $htmlContent .= "
      <h1>" . $post['title']  . "</h1>
      <div class='page-post-item'>
      <img
        src=" . $post['image'] . "
      />
      <p>
        " . $post['text'] . "
      </p>
      </div>
      </div>
      </div>
      </body>
      </html>
  ";
  echo $htmlContent;
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
?>