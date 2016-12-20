<?php

  ob_start();

  include 'sendresponse.php';
  include 'superuser.php';
  include 'serverinfo.php';

  // Log in as super user
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);
  $encryptedpassword = sha1($databasename.$username.$superusername.$password.$superuserpassword);

  // Create connection
  $conn = new mysqli($servername, $superusername, $superuserpassword, $databasename);

  // Check connection
  if ($conn->connect_error) {
    sendResponse('Connection failed: '.$conn->connect_error, false);
  }

  // Check to see if username already exists
  $sql = "SELECT * FROM users WHERE name = '{$username}'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0){
    sendResponse("User already exists", false);
  }

  // Create a new user
  $sql = "INSERT INTO users (name,password) VALUES ('{$username}',UNHEX('{$encryptedpassword}'))";
  if ($conn->query($sql) !== true) {
    sendResponse('Error adding user: '.$conn->error, false);
  }

  sendResponse('New user has been created', true);

  mysqli_close($conn);

?>
