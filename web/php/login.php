<?php

  ob_start();

  include 'superuser.php';
  include 'sendresponse.php';
  include 'serverinfo.php';
  include 'firebase.php';

  // Attempt login as normal user
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);
  $encryptedpassword = sha1($databasename.$username.$superusername.$password.$superuserpassword);

  // Check for null username and password
  if (empty($username)){
    sendResponse("Please enter a username", false);
  }

  if (empty($password)){
    sendResponse("Please enter a password", false);
  }

  // Create connection
  $conn = new mysqli($servername, $superusername, $superuserpassword, $databasename);

  // Check connection
  if ($conn->connect_error) {
    sendResponse('Connection failed: '.$conn->connect_error, false);
  }

  // Look for user in users table
  $sql = "SELECT * FROM users WHERE name = '{$username}'";
  $result = $conn->query($sql);
  if ($result->num_rows == 0){
    sendResponse("User doesn't exist", false);
  }

  // check users password
  $sql = "SELECT password FROM users WHERE name = '{$username}'";
  $result = $conn->query($sql);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $storedpassword = bin2hex($row["password"]);
  if ($storedpassword != $encryptedpassword){
    sendResponse("Invalid password", false);
  } else {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\n
        \"data\" : {\n
          \"name\" : \"Goku\",\n
          \"power_level\" : \"Over 9000\",\n
          \"fighting_skill\" : \"excellent\"\n
          },\n
          \"to\" : \"eW1zaAGunwE:APA91bEhIm9g8Kst_UgPAaPPhmcBfoI1JfY1Q87CHA7GR1D-HeMyut9ZSJxz4tLa0c2tE42w2rAyhZ4nduOPiyPgQ7FeYewPcggzJcHnnEd_O8267TWpddjAR9PSZlHa-Xs6PvPL--1_\"\n
          }",
      CURLOPT_HTTPHEADER => array(
        "authorization: key=AAAAlidsJ90:APA91bHgn-GGtJaesrCRmecBh77KaP8LqdBkRW9ng8spywONeAVSmJf9TY7N4Qw7SShyWCKVhIxWxtxSoQC7c4kFuZGQguibnAtKBZlttWd7LJIOFv9e_FqgDXRwzrtiruVXqftDvZpZyTqGGMDS4jHbxoasYLx43w",
        "cache-control: no-cache",
        "content-type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
    // $request = new HttpRequest();
    // $request->setUrl('https://fcm.googleapis.com/fcm/send');
    // $request->setMethod(HTTP_METH_POST);
    //
    // $request->setHeaders(array(
    //   'cache-control' => 'no-cache',
    //   'content-type' => 'application/json',
    //   'authorization' => 'key=AAAAlidsJ90:APA91bHgn-GGtJaesrCRmecBh77KaP8LqdBkRW9ng8spywONeAVSmJf9TY7N4Qw7SShyWCKVhIxWxtxSoQC7c4kFuZGQguibnAtKBZlttWd7LJIOFv9e_FqgDXRwzrtiruVXqftDvZpZyTqGGMDS4jHbxoasYLx43w'
    // ));
    //
    // $request->setBody('{
    //   "data" : {
    //   "name" : "Goku",
    //   "power_level" : "Over 9000",
    //   "fighting_skill" : "excellent"
    // },
    //   "to" : "fTF_RuYQ_VM:APA91bGrcl7MfoCHuGvfrlNsWzqQn_uImPeiranNbgGS8rR2N8im6B76IPveNECThSKgiiSAe6HDYDntWMsCjn9zBsQDWxoQiYI59gdTSla1TGkGV3p1LOqNGAXE0OVqxBafB9CoT_gN"
    // }');
    //
    // try {
    //   $response = $request->send();
    //
    //   echo $response->getBody();
    // } catch (HttpException $ex) {
    //   echo $ex;
    // }
    // $url = 'https://fcm.googleapis.com/fcm/send';
    // $data = array('score' => '5x1', 'time' => '15:10');
    //
    // // use key 'http' even if you send the request to https://...
    // $options = array(
    //   'http' => array(
    //     'header'  =>  "Content-type: application/json\r\n" .
    //                   "Authorization: key=AAAAlidsJ90:APA91bHgn-GGtJaesrCRmecBh77KaP8LqdBkRW9ng8spywONeAVSmJf9TY7N4Qw7SShyWCKVhIxWxtxSoQC7c4kFuZGQguibnAtKBZlttWd7LJIOFv9e_FqgDXRwzrtiruVXqftDvZpZyTqGGMDS4jHbxoasYLx43w\r\n",
    //     'method'  => 'POST',
    //     'data' => http_build_query($data),
    //     'to' => $fcm
    //   )
    // );
    // $context  = stream_context_create($options);
    // $result = file_get_contents($url, false, $context);
    // if ($result === FALSE) { /* Handle error */ }
    //
    // var_dump($result);
    //sendResponse("Login successful", true);
  }

  //sendResponse($storedpassword."   also   ".$encryptedpassword, false);
  mysqli_close($conn);

?>
