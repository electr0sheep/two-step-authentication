var xhr = new XMLHttpRequest();

function registerButtonOnClick() {
  var username = document.getElementById('username').value;
  var password = document.getElementById('password').value;
  if (username && password) {
    xhr.open('POST', "/php/new.php", true);
    xhr.onreadystatechange = processRequest;
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("username=" + username + "&password=" + password);
  }
}

function cancelButtonOnClick() {
  window.location="/";
}

async function processRequest(e) {
  if (xhr.readyState == 4 && xhr.status == 200) {
    var response = JSON.parse(xhr.responseText);
    if (response.result == true){
      bootstrap_alert.warning(response.message, 'success', 4000);
      await sleep(4000);
      window.location="/";
    } else {
      bootstrap_alert.warning(response.message, 'danger', 4000);
    }
  }
}

window.onload= function() {
  var username = document.getElementById('username');
  var password = document.getElementById('password');
  var register = document.getElementById('register');
  username.addEventListener('keypress', function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      register.click();
    }
  });
  password.addEventListener('keypress', function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      register.click();
    }
  });
};

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

bootstrap_alert = function () {};
bootstrap_alert.warning = function (message, alert, timeout) {
  $(".alert").alert('close');
  $('<div id="floating_alert" class="alert alert-' + alert + ' fade in" style="position:fixed; top:0px; left:50%; z-index:5000; transform: translateX(-50%);">' + message + '</div>').appendTo('body');

    setTimeout(function () {
      $(".alert").alert('close');
    }, timeout);
}
