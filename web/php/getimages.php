<?php

  ob_start();

  include 'sendresponse.php';
  include 'serverinfo.php';
  include 'superuser.php';

  $username = $_POST['username'];
  $password = $_POST['password'];

  $username = "electrosheep";
  $password = "billybob1";

  // Create connection
  // NOTE: the name of the database should be the same as the name of the user
  $conn = new mysqli($servername, $superusername, $superuserpassword, $databasename);

  // Check connection
  if ($conn->connect_error) {
    sendResponse('Connection failed: '.$conn->connect_error, false);
  }

  // Create queries to retrieve the data we need
  $sql = "SELECT * FROM {$username}";

  // Send the queries
  $result = $conn->query($sql);
  $ar = array();
  if ($result === false) {
    sendResponse('Error getting results: '.$conn->error, false);
  } else {
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){
      $name = $row['name'];
      $timestamp = $row['timestamp'];
      $id = $row['id'];
      $data = $row['data'];
      $data = base64_encode($data);
      array_push($ar, array(
        'name' => $name,
        'timestamp' => $timestamp,
        'id' => $id,
        'data' => $data
      ));
    }

    // Make sure that something was actually retrieved
    if (count($ar) == 0) {
      sendResponse('There are no images in the database. Upload at least one first!', false);
    }
    sendResponseWithJSONArray('Images sent successfully!', true, json_encode(array_values($ar)));
  }

  mysqli_close($con);

?>
