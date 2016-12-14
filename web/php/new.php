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

  // Create a new table, if it doesn't already exist
  $sql = 'CREATE TABLE ';
  $sql .= htmlspecialchars($_POST['username']);
  $sql .= '(timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP, id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(20) NOT NULL, data BLOB NOT NULL)';

  if ($conn->query($sql) !== true) {
    sendResponse('Error creating table: '.$conn->error, false);
  }

  // Create a new user
  $sql = "INSERT INTO '{$username}' (name,password,user_type,associated_table) VALUES ('{$username}',UNHEX('{$encryptedpassword}'),0,'{$username}')";
  if ($conn->query($sql) !== true) {
    sendResponse('Error adding user: '.$conn->error, false);
  }

  sendResponse('New user has been created', true);

  mysqli_close($conn);

?>
