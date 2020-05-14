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
      <span class="menu-items"><a href="homepage.php">Home</a></span>
      <span class="menu-items"><a href="login.php">Login</a></span>
      <span class="menu-items"><a href="signup.php">Sign Up</a></span>
    </div>
    <div id="page-post">

      <?php
      require_once 'db_info.php';
      session_start();
      global $hn, $un, $pw, $db;
      $conn = new mysqli($hn, $un, $pw, $db);
      if ($conn->connect_error) die("Connection failed!");

      $id = $_GET['postid'];
      $ID = sanitizeMySQL($conn, $id); //something wrong with this line

      $query = "SELECT title,text,image FROM Post WHERE id='" . $ID . "'";
      $q = mysqli_query($conn, $query);
      $post = mysqli_fetch_assoc($q);


      //maybe
      //$row['author_id']

      echo "
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
