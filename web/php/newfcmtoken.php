<?php

  ob_start();

  include 'superuser.php';
  include 'sendresponse.php';
  include 'serverinfo.php';

  // Attempt login as normal user
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);
  $fcm_token = htmlspecialchars($_POST['fcm_token']);
  $encryptedpassword = sha1($databasename.$username.$superusername.$password.$superuserpassword);

  // Check for null username and password
  if (empty($username)){
    sendResponse("Please enter a username", false);
  }

  if (empty($password)){
    sendResponse("Please enter a password", false);
  }

  // Create connection
  $conn = new mysqli($servername, $superusername, $superuserpassword, $databasename);

  // Check connection
  if ($conn->connect_error) {
    sendResponse('Connection failed: '.$conn->connect_error, false);
  }

  // Look for user in users table
  $sql = "SELECT * FROM users WHERE name = '{$username}'";
  $result = $conn->query($sql);
  if ($result->num_rows == 0){
    sendResponse("User doesn't exist", false);
  }

  // check user's password
  $sql = "SELECT password FROM users WHERE name = '{$username}'";
  $result = $conn->query($sql);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $storedpassword = bin2hex($row["password"]);
  if ($storedpassword != $encryptedpassword){
    sendResponse("Invalid password", false);
  }

  // check to see if two-step authentication has already been set up
  $sql = "SELECT fcm_token FROM users WHERE name = '{$username}'";
  $result = $conn->query($sql);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $value = $row["fcm_token"];
  if (!empty($value)){
    sendResponse("Two-step authentication has already been set up", false);
  }

  // store the two step credentials

  $sql = "UPDATE users SET fcm_token = '{$fcm_token}' WHERE name = '{$username}'";
  if ($conn->query($sql) !== true) {
    sendResponse('Error updating user: '.$conn->error, false);
  }

  mysqli_close($conn);

  sendResponse("Two-step authentication has successfully been set up", true);

?>
