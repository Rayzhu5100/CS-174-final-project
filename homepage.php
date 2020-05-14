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
          <div id="homepage-container">
            <div class="top-menu">
              <span class="menu-items"><a href="homepage.php">Home</a></span>             
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
    <span class="menu-items"><a href="logout.php">Logout</a></span>';
  }
  $htmlContent .= '
      </div>      
      <div class="items-container">     
  ';
  return $htmlContent;
}

function main()
{
  $formDoc = createFormDoc();
  $htmlContent = htmlMenu();
  global $hn, $un, $pw, $db;

  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) {
    die("Sorry!!! Our server is busy right now. Please come back later.");
  } else {
    echo $formDoc;
    $query = "SELECT id,title,image,author_id FROM Post";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) >= 1) {
      while ($row = mysqli_fetch_assoc($result)) {
        $htmlContent .= "
        <div class='item'>
          <a href='post.php?postid=" . $row['id'] . "'>
            <div class='img'>
              <img
                src='" . $row["image"] . "'
              />
            </div>
            <div class='desc'>
              <p>" . $row['title'] . "</p>
            </div>
          </a>
        </div>
        ";
      }
    } else {
      $htmlContent .= "<span class='error'>There is no post...</span>";
    }
    $htmlContent .= "
              </div>
            </div>
          </body>
        </html>
      ";
    echo $htmlContent;
  }
}

main();
