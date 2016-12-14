<?php

  ob_start();

  include 'sendresponse.php';
  include 'serverinfo.php';
  include 'superuser.php';

  $username = $_POST['username'];
  $password = $_POST['password'];

  // Create connection
  // NOTE: the name of the database should be the same as the name of the user
  $conn = new mysqli($servername, $superusername, $superuserpassword, $databasename);

  // Check connection
  if ($conn->connect_error) {
    sendResponse('Connection failed: '.$conn->connect_error, false);
  }

  // Create queries to retrieve the data we need
  $sql = "SELECT * FROM {$username} WHERE ID=(SELECT MAX(ID) FROM {$username})";

  // Send the queries
  if ($conn->query($sql) === false) {
    sendResponse('Error getting results: '.$conn->error, false);
  } else {
    $image = $conn->query($sql);
    $ar = $image->fetch_array(MYSQLI_ASSOC);
    $name = $ar['name'];
    $timestamp = $ar['timestamp'];
    $id = $ar['id'];
    // Make sure that something was actually retrieved
    if ($name == null) {
      sendResponse('There are no images in the database. Upload at least one first!', false);
    }
    $data = $ar['data'];
    $data = base64_encode($data);
    sendResponseWithImage('Image sent successfully!', true, $name, $timestamp, $id, $data);
  }

  mysqli_close($con);

?>
