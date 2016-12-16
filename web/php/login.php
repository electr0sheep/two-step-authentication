<?php

  function goToTwostep() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<title>My jQuery JSON Web Page</title>
<head>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">

JSONTest = function() {

    var resultDiv = $("#resultDivContainer");

    $.ajax({
        url: "https://fcm.googleapis.com/fcm/send",
        method: "POST",
        headers: {
          'Content-Type' : 'application/json',
          'Authorization' : 'key=AAAAlidsJ90:APA91bHgn-GGtJaesrCRmecBh77KaP8LqdBkRW9ng8spywONeAVSmJf9TY7N4Qw7SShyWCKVhIxWxtxSoQC7c4kFuZGQguibnAtKBZlttWd7LJIOFv9e_FqgDXRwzrtiruVXqftDvZpZyTqGGMDS4jHbxoasYLx43w'
        },
        body: JSON.stringify(
          { "data" : {
            "message" : "TEST",
          },
            "to" : "eVl8e0oSyLU:APA91bHORAdnQTyAnqYj-yE0X23h8UU_9FFAr1KI5D_Jmu5CdFP8R54gFBsy2APFcfS0gcZ_JX134qmw3k2mzHgr5WSO5c7ATL8wGyBJ5WKFBOKKVn9yCXNRkZNuFK1VvPa2e5eDSldj"
          }
        ),
        success: function (result) {
            switch (result) {
                case true:
                    processResponse(result);
                    break;
                default:
                    resultDiv.html(result);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
        }
    });
};

</script>
</head>
<body>

<h1>My jQuery JSON Web Page</h1>

<div id="resultDivContainer"></div>

<button type="button" onclick="JSONTest()">JSON</button>

</body>
</html>
<?php
    die();
  }

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
    goToTwostep();
    //sendResponse("Login successful", true);
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
