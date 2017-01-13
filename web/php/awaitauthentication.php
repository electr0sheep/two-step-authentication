<?php
  $username = htmlspecialchars($_POST['username']);
?>

<form id="myForm" action="/php/authenticationsuccess.php" method="post">
  <input type="hidden" name="username" value=<?php echo $username ?>>
</form>

Awaiting authentication...
<script>
  var xhr = new XMLHttpRequest();

  function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }

  async function processRequest(e) {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.result == true){
        // go to success page
        document.getElementById('myForm').submit();
      } else {
        await sleep(2000);
        checkDatabase();
      }
    }
  }

  function checkDatabase() {
    xhr.open('POST', "/php/checkauthenticated.php", true);
    xhr.onreadystatechange = processRequest;
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("username=<?php echo $username ?>");
  }

  checkDatabase(); // start the process...
</script>
