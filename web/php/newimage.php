<?php

  ob_start();

  include 'sendresponse.php';
  include 'serverinfo.php';
  include 'superuser.php';

  $username = $_POST['username'];
  $password = $_POST['password'];
  $name = $_POST['imagename'];
  $data = $_POST['imagedata'];
  $buffer = base64_decode($data);

  // Create connection
  $conn = new mysqli($servername, $superusername, $superuserpassword, $databasename);

  // Check connection
  if ($conn->connect_error) {
    sendResponse('Connection failed: '.$conn->connect_error, false);
  }

  $buffer = $conn->real_escape_string($buffer);

  // NOTE: the name of the table should be the same as the name of the user
  $sql = "INSERT INTO {$username} (name, data) VALUES ('{$name}', '{$buffer}')";

  if ($conn->query($sql) !== true) {
    sendResponse('Error adding image: '.$conn->error, false);
  }

  sendResponse('Image added successfully!', true);

  mysqli_close($con);

?>
