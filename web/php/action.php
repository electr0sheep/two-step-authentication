<?php

  ob_start();

  // Get crap from post
  $action = htmlspecialchars($_POST['action']);
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);

  print_r($_POST);

  echo("\n");

  echo($action);

  echo("\n");

  if ($action == "login"){
    echo "Selected login";
  } elseif ($action == "new"){
    echo "Selected new";
  } elseif ($action == "reset"){
    echo "Selected reset";
  } else {
    // ERROR
    echo "ERROR";
  }

?>
