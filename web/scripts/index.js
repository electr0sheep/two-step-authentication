// http://fiddle.jshell.net/onigetoc/Xa6a7/

var xhr = new XMLHttpRequest();
var awaitingAuthentication = false;

function createNewUserButtonOnClick() {
  window.location="/newuser.html";
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
  if (username.length > 0) {
    bootstrap_alert.warning("Sorry " + username + ", this functionality does not exist at this time", 'warning', 4000);
  } else {
    bootstrap_alert.warning("Sorry, this functionality does not exist at this time", 'warning', 4000);
  }
}

async function processRequest(e) {
  if (xhr.readyState == 4 && xhr.status == 200) {
    var response = JSON.parse(xhr.responseText);
    if (response.result == true){
      showAuthenticationModal();
      awaitingAuthentication = true;
      checkDatabase(); // start the process...
      // document.getElementById('login').submit();
    } else {
      bootstrap_alert.warning(response.message, 'danger', 4000);
    }
  }
}

window.onload= function() {
  var username = document.getElementById('username');
  var password = document.getElementById('password');
  username.addEventListener('keypress', function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      loginButtonOnClick();
    }
  });
  password.addEventListener('keypress', function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      loginButtonOnClick();
    }
  });
};

bootstrap_alert = function () {};
bootstrap_alert.warning = function (message, alert, timeout) {
  $(".alert").alert('close');
  $('<div id="floating_alert" class="alert alert-' + alert + ' fade in" style="position:fixed; top:0px; left:50%; z-index:5000; transform: translateX(-50%);">' + message + '</div>').appendTo('body');

    setTimeout(function () {
      $(".alert").alert('close');
    }, timeout);
}

function showAuthenticationModal() {
  $("#authentication-modal").modal({backdrop: 'static', keyboard: false});
  // send ajax request and on completion, go to welcome page
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function checkAuthentication(e) {
  if (xhr.readyState == 4 && xhr.status == 200) {
    var response = JSON.parse(xhr.responseText);
    if (response.result == true){
      // go to success page
      document.getElementById('login').submit();
    } else {
      if (awaitingAuthentication) {
        await sleep(2000);
        checkDatabase();
      }
    }
  }
}

function checkDatabase() {
  var username = document.getElementById('username').value;
  xhr.open('POST', "/php/checkauthenticated.php", true);
  xhr.onreadystatechange = checkAuthentication;
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("username=" + username);
}

function cancelAuthenticationRequestButtonOnClick() {
  awaitingAuthentication = false;
}
