<?php

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
  if ($conn->query($sql) !== true) {
    sendResponse('Error deleting user: '.$conn->error, false);
  }

  $result = mysql_fetch_result($conn,0,'pending_authentication');
  if ($result == 1){
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
