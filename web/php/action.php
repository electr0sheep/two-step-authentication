<?php

  ob_start();

  // Get crap from post
  $action = htmlspecialchars($_POST['action']);
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);

  if ($action == "login"){
?>

<form id="myForm" action="/php/login.php" method="post">
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
  } elseif ($action == "new"){
?>

<form id="myForm" action="/php/new.php" method="post">
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
  } elseif ($action == "resetpw"){
?>

<form id="myForm" action="/php/resetpw.php" method="post">
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
  } elseif ($action == "dlapk"){
?>
    <script>
      var link = document.createElement("a");
      link.href = "/data/twostep.apk";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      delete link;
    </script>
<?php
  } else {
    // ERROR
    echo "ERROR";
  }

?>
