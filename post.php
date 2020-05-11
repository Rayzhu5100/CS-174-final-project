<form method="post" action="" enctype='multipart/form-data'>
  <input type="text" name="text">
  <input type='file' name='file' />
  <input type='submit' value='Save' name='upload'>
</form>
<form method="get" action="post.php">
    <input type="text" name="id" value="">
    <input type="submit">
</form>

<?php
require_once 'loginpas.php';
echo $_POST['username'];

if(isset($_POST['upload'])){

  global $hn, $un, $pw, $db;
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error)die("Connection failed!");

  $name = $_FILES['file']['name'];
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

    session_start();
    $id = $_SESSION['id'];

    $Image = sanitizeMySQL($conn,$image);
    $image_text = sanitizeMySQL($conn,sanitizeString($_POST['text']));
    $ID = sanitizeMySQL($conn,$id);

    $stmt = $conn->prepare("INSERT INTO Post(text,image,author_id) VALUES (?,?,?)");
    $stmt->bind_param('sss',$image_text,$Image,$ID);
    $stmt->execute();

    $stmt->close();
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
