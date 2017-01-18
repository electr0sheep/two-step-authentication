var xhr = new XMLHttpRequest();

function createNewUserButtonOnClick() {
  showAlertModal("You clicked the new user button");
}

function loginButtonOnClick() {
  var username = document.getElementById('username').value;
  var password = document.getElementById('password').value;
  xhr.open('POST', "/php/login.php", true);
  xhr.onreadystatechange = processRequest;
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("username=" + username + "&password=" + password);
}

function resetpwButtonOnClick() {
  var username = document.getElementById('username').value;
  // show reset pw modal
  showAlertModal("You (" + username + ") just clicked the reset pw button");
}

async function processRequest(e) {
  if (xhr.readyState == 4 && xhr.status == 200) {
    var response = JSON.parse(xhr.responseText);
    if (response.result == true){
      // show
      window.location = '/php/awaitauthentication.php';
    } else {
      showAlertModal(response.message);
    }
  }
}

function showAlertModal(message) {
  $('#alert-modal').modal().find('.modal-body').text(message);
}
