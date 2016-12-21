<?php

  $username = htmlspecialchars($_POST['username']);

  //Connect to the database
  include 'serverinfo.php';
  include 'superuser.php';

  // Create connection
  $conn = new mysqli($servername, $superusername, $superuserpassword, $databasename);

  // Check connection
  if ($conn->connect_error) {
    sendResponse('Connection failed: '.$conn->connect_error, false);
  }

  // Check status of pending_authentication
  $sql = "SELECT pending_authentication FROM users WHERE name = '{$username}'";
  $result = $conn->query($sql);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $authenticated = $row["pending_authentication"];

  echo("WE ARE HERE\n");
  if ($authenticated == 0){
    echo("NOW WE ARE HERE\n");
?>
      <form id="myForm" action="/php/authenticationsuccess.php" method="post">
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

?>
