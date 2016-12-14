<?php

  ob_start();

  // Get crap from post
  $action = htmlspecialchars($_POST['action']);
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);

  print_r($_POST);

  if (strcmp($action, "login")){
    echo "Selected login";
  } elseif (strcmp($action, "new")){
    echo "Selected new";
  } elseif (strcmp($action, "reset")){
    echo "Selected reset";
  } else {
    // ERROR
    echo "ERROR";
  }

?>
