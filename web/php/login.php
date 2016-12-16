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
  } else {
    $url = 'https://fcm.googleapis.com/fcm/send';
    $data = array('data' => array('score' => '5x1', 'time' => '15:10'), 'to' => 'eVl8e0oSyLU:APA91bHORAdnQTyAnqYj-yE0X23h8UU_9FFAr1KI5D_Jmu5CdFP8R54gFBsy2APFcfS0gcZ_JX134qmw3k2mzHgr5WSO5c7ATL8wGyBJ5WKFBOKKVn9yCXNRkZNuFK1VvPa2e5eDSldj');

    // use key 'http' even if you send the request to https://...
    $options = array(
      'http' => array(
        'header'  => "Content-type: application/json\rAuthorization: AAAAlidsJ90:APA91bHgn-GGtJaesrCRmecBh77KaP8LqdBkRW9ng8spywONeAVSmJf9TY7N4Qw7SShyWCKVhIxWxtxSoQC7c4kFuZGQguibnAtKBZlttWd7LJIOFv9e_FqgDXRwzrtiruVXqftDvZpZyTqGGMDS4jHbxoasYLx43w\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
      )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { echo('<script>alert("whoops")</script>'); }

    echo(var_dump($result));
    echo('<script>');
    echo('alert("here")');
    echo('</script>');
    sendResponse("Login successful", true);
  }

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

  mysqli_close($conn);

?>
