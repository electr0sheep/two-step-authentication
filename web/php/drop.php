<?php

  ob_start();

  include 'sendresponse.php';
  include 'superuser.php';
  include 'serverinfo.php';

  // Log in as super user
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);

  // Create connection
  $conn = new mysqli($servername, $superusername, $superuserpassword, $databasename);

  // Check connection
  if ($conn->connect_error) {
    sendResponse('Connection failed: '.$conn->connect_error, false);
  }

  // Drop the table, if it exists
  $sql = "DROP TABLE {$username}";
  if ($conn->query($sql) !== true) {
    sendResponse('Error dropping table: '.$conn->error, false);
  }

  // Drop the user
  $sql = "DELETE FROM users WHERE name = '{$username}'";
  if ($conn->query($sql) !== true) {
    sendResponse('Error deleting user: '.$conn->error, false);
  }

  sendResponse("User removed successfully", true);

  mysqli_close($conn);

?>
