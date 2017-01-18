var xhr = new XMLHttpRequest();

function createNewUserButtonOnClick() {
  $('#alert-modal').modal().find('.modal-body').text("You clicked the new user button");
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
  $('#alert-modal').modal().find('.modal-body').text("You (" + username + ") just clicked the reset pw button");
}

function dlapkButtonOnClick() {
  // download file
  $('#alert-modal').modal().find('.modal-body').text("You clicked the dl button");
}

async function processRequest(e) {
  if (xhr.readyState == 4 && xhr.status == 200) {
    var response = JSON.parse(xhr.responseText);
    if (response.result == true){
      // show
      alert(response.message)
      window.location = '/php/awaitauthentication.php';
    } else {
      alert(response.message);
    }
  }
}
