<?php
  $username = htmlspecialchars($_POST['username']);
?>

Awaiting authentication...
<script>
  var xhr = new XMLHttpRequest();

  function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }

  async function processRequest(e) {
    if (xhr.readyState == 4 && xhr.status == 200) {
      alert(xhr.responseText);
      var response = JSON.parse(xhr.responseText);
      if (response[0].result == true){
        alert("success");
      } else {
        alert(JSON.stringify(response[0]));
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
