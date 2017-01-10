<?php
  $username = htmlspecialchars($_POST['username']);
?>

Awaiting authentication...
<script>
  var interval = null;
  var formData = {username: "<?php echo $username ?>"};

  function processRequest(e) {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.result == true){
        alert("success");
        clearInterval(interval);
      }
    }
  }


  var checkDatabase = function() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', "/php/checkauthenticated.php", true);
    xhr.onreadystatechange = processRequest;
    xhr.send();
  }
  interval = setInterval(checkDatabase, 2000);
  // checkDatabase(); // start the process...
</script>
