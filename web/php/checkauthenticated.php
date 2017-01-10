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
  $sql = "SELECT pending_authentication FROM users WHERE name = '{$username}'";
  $result = $conn->query($sql);
  // $row = $result->fetch_array(MYSQLI_ASSOC);
  // $authenticated = $row["pending_authentication"];

  if ($authenticated == "0") {
    sendResponse($result, true);
  } else {
    sendResponse($result, false);
  }
?>
