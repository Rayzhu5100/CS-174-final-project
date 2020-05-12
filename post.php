<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Add Posts</title>
</head>
<body>
    <div id="main-container">
        <h1>Add a new post</h1>
        <div id="container">
            <div id="container-post">
                <form method="post" action="post.php" enctype="multipart/form-data">
                    <p>Description</p>
                    <textarea name="description"></textarea>
                    <p>Image</p>
                    <input type="file" name="file">
                    <div id="buttons">
                        <button name ="upload">Add a post</button>
                        <button>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>








<?php
session_start();
require_once 'loginpas.php';
$id = $_SESSION['id'];
echo $id;
if(isset($_POST['upload'])){
  echo "hello";
  global $hn, $un, $pw, $db;
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error)die("Connection failed!");

  $name = $_FILES['file']['name']; // is it title?
  $target_dir = "upload/";
  $target_file = $target_dir . basename($_FILES["file"]["name"]);


  // Select file type
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Valid file extensions
  $extensions_arr = array("jpg","jpeg","png","gif");

  // Check extension
  if( in_array($imageFileType,$extensions_arr) ){



    // Convert to base64
    $image_base64 = base64_encode(file_get_contents($_FILES['file']['tmp_name']) );
    $image = 'data:image/'.$imageFileType.';base64,'.$image_base64;

    echo "base64 code is: ".$image;

    $Image = sanitizeMySQL($conn,$image);
    $image_text = sanitizeMySQL($conn,sanitizeString($_POST['description']));
    $ID = sanitizeMySQL($conn,$id);

    $stmt = $conn->prepare("INSERT INTO Post(text,image,author_id) VALUES (?,?,?)");
    $stmt->bind_param('sss',$image_text,$Image,$ID);
    $stmt->execute();
  }else echo "Wrong image type!";
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

?>
