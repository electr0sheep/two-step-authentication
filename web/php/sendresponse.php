<?php

  function sendResponse($message, $result) {
    $jsonoutput = array();
    ob_clean();
    $bus = array(
      'result' => $result,
      'message' => $message,
    );
    array_push($jsonoutput, $bus);
    exit(json_encode($jsonoutput));
  }

  function sendResponseWithImage($message, $result, $name, $timestamp, $id, $data) {
    $jsonoutput = array();
    ob_clean();
    $bus = array(
      'result' => $result,
      'message' => $message,
      'name' => $name,
      'timestamp' => $timestamp,
      'id' => $id,
      'data' => $data
    );
    array_push($jsonoutput, $bus);
    exit(json_encode($jsonoutput));
  }

  function sendResponseWithJSONArray($message, $result, $array){
    $jsonoutput = array();
    ob_clean();
    $bus = array(
      'result' => $result,
      'message' => $message,
      'array' => $array
    );
    array_push($jsonoutput, $bus);
    exit(json_encode($jsonoutput));
  }

?>
