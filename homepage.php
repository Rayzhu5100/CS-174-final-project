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
          <span class="menu-items"><a href="login.php">Login</a></span>
          <span class="menu-items"><a href="signup.php">Sign Up</a></span>
      </div>

      <div class="items-container">
        <!-- <div class="item">
          <a>
            <div class="img">
              <img
                src="https://images.unsplash.com/reserve/bOvf94dPRxWu0u3QsPjF_tree.jpg?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2255&q=80"
              />
            </div>
            <div class="desc">
              <p>Australia</p>
            </div>
          </a>
        </div>
      </div>
    </div>
  </body>
</html> -->

<?php
require_once 'loginpas.php';
session_start();
global $hn, $un, $pw, $db;
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error)die("Connection failed!");

 $r="SELECT id,title,image,author_id FROM Post";
 $q=mysqli_query($conn,$r);
 if(mysqli_num_rows($q)>=1){
   while($row=mysqli_fetch_assoc($q)){
      echo "
      <div class='item'>
        <a href='viewPost.php?postid=" . $row['id']. "'>
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
        //print id, image and text here
          // $row['title']
          // $row['image']
           //$row['author_id']

           //pass id to next page
          // $id = $row['id'];
          // $_SESSION['id'] = $id;
           // this one successfully rightyes
           //header('Location: viewPost.php');
           //exit;
 }
}
echo "
</div>
</div>
</body>
</html>
";
