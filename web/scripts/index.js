// http://fiddle.jshell.net/onigetoc/Xa6a7/

var xhr = new XMLHttpRequest();

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
      document.getElementById('login').submit();
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
  $('<div id="floating_alert" class="alert alert-' + alert + ' fade in">' + message + '</div>').appendTo('body');

    setTimeout(function () {
      $(".alert").alert('close');
    }, timeout);
}
