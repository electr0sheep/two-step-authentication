<?php

  ob_start();

  include 'sendresponse.php';
  include 'superuser.php';
  include 'serverinfo.php';

  // Check that username and password exist
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);

  // Check for null username and password
  if (empty($username)){
    sendResponse("Please enter a username", false);
  }

  if (empty($password)){
    sendResponse("Please enter a password", false);
  }

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

  // Check password hash to make sure that it hasn't been breached
  $pwsha1 = sha1($password);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.pwnedpasswords.com/range/".substr($pwsha1,0,5));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $pwnedResponse = curl_exec($ch);
  $pwnedResponseAr = explode(PHP_EOL, $pwnedResponse);
  error_log('PWNEDRESPONSE TYPE: '.gettype($pwnedResponseAr));
  error_log('NUMBER OF THINGS IN PWNEDRESPONSE: '.count($pwnedResponseAr));
  error_log('PWNEDRESPONSE: '.$pwnedResponseAr);
  if ($pwnedResponse === FALSE) {
    sendResponse('Error adding user: '.curl_error($ch));
  } else {
    error_log('WE ARE AT THE FOREACH');
    foreach($pwnedResponseAr as $value) {
      echo('HERE IS A VALUE: '.$value);
      $ar = explode(':', $value);
      if ($ar[0] == substr($pwsha1, 5)) {
        sendResponse('Unable to create new user, that password has been seen in breaches '.$ar[1].' times!');
      }
    }
  }
  curl_close($ch);

  // Create a new user
  $sql = "INSERT INTO users (name,password) VALUES ('{$username}',UNHEX('{$encryptedpassword}'))";
  if ($conn->query($sql) !== true) {
    sendResponse('Error adding user: '.$conn->error, false);
  }

  sendResponse('New user has been created', true);

  mysqli_close($conn);

?>
