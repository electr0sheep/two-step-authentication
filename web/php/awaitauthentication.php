<?php
  $username = htmlspecialchars($_POST['username']);
?>

Awaiting authentication...
<script>
  var interval = null;
  var formData = {username: "<?php echo $username ?>"};
  var xhr = new XMLHttpRequest();

  function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }

  async function processRequest(e) {
    if (xhr.readyState == 4 && xhr.status == 200) {
      alert(JSON);
      var response = JSON.parse(xhr.responseText);
      if (response.result == true){
        alert("success");
        clearInterval(interval);
      } else {
        alert("nope");
        await sleep(2000);
        checkDatabase();
      }
    }
  }

  function checkDatabase() {
    xhr.open('POST', "/php/checkauthenticated.php", true);
    xhr.onreadystatechange = processRequest;
    xhr.send();
  }

  checkDatabase(); // start the process...
</script>
