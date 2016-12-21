<?php
  $username = htmlspecialchars($_POST['username']);
?>

Awaiting authentication...
<script>
    $(function(){
      function checkDatabase()
      {
        $('h1.countdown').load("checkauthenticated.php", {username:<?php echo($username) ?>});
        setTimeout(loadNum, 5000); // makes it reload every 5 sec
      }
      checkDatabase(); // start the process...
    });
 </script>
