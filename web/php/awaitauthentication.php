<?php
  $username = htmlspecialchars($_POST['username']);
?>

Awaiting authentication...
<script>
  setInterval(function checkDatabase() {
    var formData = {username: "<?php echo $username ?>"};
    $.ajax({
      url : "/php/checkauthenticated.php",
      type: "POST",
      data : formData,
      success : function(data, textStatus, jqXHR)
      {
        console.log(data);
      },
      error : function (jqXHR, textStatus, errorThrown)
      {

      }
    });
  }, 1000);
  checkDatabase(); // start the process...
</script>
