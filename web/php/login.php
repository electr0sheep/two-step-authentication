<?php

  ob_start();

  include 'superuser.php';
  include 'sendresponse.php';
  include 'serverinfo.php';
  include 'firebase.php';

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

  // check to see if two-step authentication has been set up
  $sql = "SELECT fcm_token FROM users WHERE name = '{$username}'";
  $result = $conn->query($sql);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $fcm_token = $row["fcm_token"];
  if (empty($fcm_token)){
    sendResponse("Two-step authentication has not been set up", false);
  }

  // send the two-step authentication notification to android device

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n
      \"notification\" : {\n
        \"title\" : \"Authentication requested\",\n
        \"icon\" : \"ic_stat_name\",\n
        \"click_action\" : \"com.electrosheep.twostepauthentication.AUTHENTICATE\",\n
      },\n
      \"to\" : \"{$fcm_token}\"\n
      \"priority\" : \"high\"\n
      }",
    CURLOPT_HTTPHEADER => array(
      "authorization: key=AAAA3hb7KGI:APA91bGmRa_-zerMJduNHLIdOZSeVq1tWm5yqgV88TqmZRWjrUAjxZIKcd8Cyssx5fWJSxt4cqef4tYVBA8t2pQ9qZP7i2g2MEBbjJSf-A0DvB8hrnuf3zJo84PMHuDqJB6Xa2Ji0LbWlJqz73-OPgTFvR0QCijC7Q",
      "cache-control: no-cache",
      "content-type: application/json"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }

  mysqli_close($conn);

?>
