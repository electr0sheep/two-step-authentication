var xhr = new XMLHttpRequest();

function loginButtonOnClick() {
  var username = document.getElementById('username').value;
  var password = document.getElementById('password').value;
  xhr.open('POST', "/php/login.php", true);
  xhr.onreadystatechange = processRequest;
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("username=" + username + "&password=" + password);
}

function newUserButtonOnClick() {
  // show new user modal
  alert("You clicked the new user modal");
}

function resetpwButtonOnClick() {
  var username = document.getElementById('username').value;
  // show reset pw modal
  alert("You clicked the reset pw button");
}

function dlapkButtonOnClick() {
  // download file
  alert("You clicked the dl button");
}

async function processRequest(e) {
  if (xhr.readyState == 4 && xhr.status == 200) {
    var response = JSON.parse(xhr.responseText);
    if (response.result == true){
      alert("Seems like it worked")
    } else {
      alert("Something went wrong");
    }
  }
}
