<?php

  $username = htmlspecialchars($_POST['username']);

  //Connect to the database
  include 'serverinfo.php';
  include 'superuser.php';
  include 'sendresponse.php';

  // Create connection
  $conn = new mysqli($servername, $superusername, $superuserpassword, $databasename);

  // Check connection
  if ($conn->connect_error) {
    sendResponse('Connection failed: '.$conn->connect_error, false);
  }

  // Check status of pending_authentication
  $sql = "SELECT * FROM users WHERE name = '{$username}'";
  $result = $conn->query($sql);

  if (!$result){
    sendResponse(mysqli_error($result) . "\n" . mysqli_errno(), false);
  }
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $authenticated = $row["pending_authentication"];

  if ($authenticated == 0) {
    sendResponse($sql, true);
  } else {
    sendResponse($sql, false);
  }
?>
