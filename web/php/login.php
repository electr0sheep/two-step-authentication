<?php

  ob_start();

  include 'superuser.php';
  include 'sendresponse.php';
  include 'serverinfo.php';
  include 'firebase.php';

  // Attempt login as normal user
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);
  // Make sure username is lowercase
  $username = strtolower($username);
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
    sendResponse("Two-step authentication has not been set up. Please login via the android app to set it up.", false);
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
        \"sound\" : \"default\"\n
      },\n
      \"to\" : \"{$fcm_token}\"\n
      \"priority\" : \"high\"\n
      }",
    CURLOPT_HTTPHEADER => array(
      "authorization: key={$FIREBASE_SERVER_KEY}",
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
    // go to awaitauthentication and update sql table
    $sql = "UPDATE users SET pending_authentication = 1 WHERE name = '{$username}'";
    if ($conn->query($sql) !== true){
      sendResponse('Error updating table: '.$conn->error, false);
    }
?>
    <form id="myForm" action="/php/awaitauthentication.php" method="post">
<?php
        foreach ($_POST as $a => $b) {
            echo '<input type="hidden" name="'.htmlentities($a).'" value="'.htmlentities($b).'">';
        }
?>
    </form>
    <script type="text/javascript">
        document.getElementById('myForm').submit();
    </script>
<?php
  }

  sendResponse("Notification sent to phone", true);

  mysqli_close($conn);

?>
