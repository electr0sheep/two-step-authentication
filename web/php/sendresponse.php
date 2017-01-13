<?php

  function sendResponse($message, $result) {
    ob_clean();
    $jsonoutput = array(
      'result' => $result,
      'message' => $message,
    );
    exit(json_encode($jsonoutput));
  }

  function sendResponseWithImage($message, $result, $name, $timestamp, $id, $data) {
    ob_clean();
    $jsonoutput = array(
      'result' => $result,
      'message' => $message,
      'name' => $name,
      'timestamp' => $timestamp,
      'id' => $id,
      'data' => $data
    );
    exit(json_encode($jsonoutput));
  }

  function sendResponseWithJSONArray($message, $result, $array){
    ob_clean();
    $jsonoutput = array(
      'result' => $result,
      'message' => $message,
      'array' => $array
    );
    exit(json_encode($jsonoutput));
  }

?>
