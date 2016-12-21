<?php
  $username = htmlspecialchars($_POST['username']);
?>

Awaiting authentication...
<script>
  setInterval(function checkDatabase() {
    load("checkauthenticated.php", {username:<?php echo($username) ?>});
  }, 1000);
  checkDatabase(); // start the process...
</script>
