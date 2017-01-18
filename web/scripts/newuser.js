var xhr = new XMLHttpRequest();

function registerButtonOnClick() {
  var username = document.getElementById('username').value;
  var password = document.getElementById('password').value;
  xhr.open('POST', "/php/new.php", true);
  xhr.onreadystatechange = processRequest;
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("username=" + username + "&password=" + password);
}

function cancelButtonOnClick() {
  window.location="/";
}

async function processRequest(e) {
  if (xhr.readyState == 4 && xhr.status == 200) {
    var response = JSON.parse(xhr.responseText);
    if (response.result == true){
      showAlertModal(response.message);
      await sleep(3000);
      window.location="/";
    } else {
      showAlertModal(response.message);
    }
  }
}

function showAlertModal(message) {
  $('#alert-modal').modal().find('.modal-body').text(message);
}

window.onload= function() {
  var username = document.getElementById('username');
  var password = document.getElementById('password');
  username.addEventListener('keypress', function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      registerButtonOnClick();
    }
  });
  password.addEventListener('keypress', function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      registerButtonOnClick();
    }
  });
};

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}
