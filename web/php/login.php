<?php

  ob_start();

  include 'superuser.php';
  include 'sendresponse.php';
  include 'serverinfo.php';

  // Attempt login as normal user
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);
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

  // check users password
  $sql = "SELECT password FROM users WHERE name = '{$username}'";
  $result = $conn->query($sql);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $storedpassword = bin2hex($row["password"]);
  if ($storedpassword != $encryptedpassword){
    sendResponse("Invalid password", false);
  }
  sendResponse("Login successful", true);

  mysqli_close($conn);
  sendResponse($storedpassword."   also   ".$encryptedpassword, false);


  // Check connection
  if ($conn->connect_error){
    // At this point either the username or password is wrong
    // Figure out which one it is

    // Check if user exists
    // To do so, we need a valid connection
    $conn2 = new mysqli($servername, $superusername, $superuserpassword);
    $sql = "SELECT USER FROM mysql.user WHERE user = '" . $username . "'";
    $result = $conn2->query($sql);
    if ($result->num_rows > 0){
      $message = "Invalid password";
    } else {
      $message = "User doesn't exist";
    }
    sendResponse($message, false);
  }

  sendResponse("Login successful", true);

  mysqli_close($conn);

?>
