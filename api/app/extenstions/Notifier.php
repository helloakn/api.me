<?php
namespace Extenstions;
use API\Schema\Database;
use API\providers\S3;
use API\providers\Env;

class Notifier{

   
  function sio_message($message, $data) {
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    $result = socket_connect($socket, '127.0.0.1', 8080);
    if(!$result) {
        die('cannot connect '.socket_strerror(socket_last_error()).PHP_EOL);
    }
    $bytes = socket_write($socket, json_encode(Array("msg" => $message, "data" => $data)));
    socket_close($socket);
}

    static function Notify($notifyTo,$data)
    {
        $data = array(
            "type"=>"notify",
            "target"=>$notifyTo,
            "data"=>$data
        );
        echo "hello world";
        $host    = Env::get('NOTI_SOCKET_SVR');
        echo $host;
        $port    = Env::get('NOTI_SOCKET_PORT');
        echo $port;
/*
$in = "GET / HTTP/1.1\r\n";
//$in = "Origin: http://api.searchapp.localhost\r\n";
$in .= "Host: 127.0.0.1:2000\r\n";
$in .= "Connection: Upgrade\r\n\r\n";
$in .= "Upgrade: websocket\r\n";


//$in .= "Sec-WebSocket-Extensions: permessage-deflate; client_max_window_bits\r\n\r\n";
//$in .= "Sec-WebSocket-Key: pJbv4rhLw2MqEoJTakNzQQ==\r\n\r\n";
//$in .= "Sec-WebSocket-Version: 13\r\n\r\n";

$in .= "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.106 Safari/537.36\r\n\r\n";
*/$out = '';
/*
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    $result = socket_connect($socket, '127.0.0.1', 2000);
    if(!$result) {
        die('cannot connect '.socket_strerror(socket_last_error()).PHP_EOL);
    }
    */
   // $data = "hello";
    $message = " this is msg"; 
    $key=base64_encode(openssl_random_pseudo_bytes(16));
    $header = "GET / HTTP/1.1\r\n"
    ."Host: $host\r\n"
    ."pragma: no-cache\r\n"
    ."Upgrade: WebSocket\r\n"
    ."Connection: Upgrade\r\n"
    ."Origin: http://api.searchapp.localhost\r\n"
    ."Sec-WebSocket-Key: $key\r\n"
    ."Sec-WebSocket-Version: 13\r\n";
    if(!empty($headers)) foreach($headers as $h) $header.=$h."\r\n";
      
    // Add end of header marker
    $header.="\r\n";

    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    $result = socket_connect($socket, $host , $port);
    if(!$result) {
        die('cannot connect '.socket_strerror(socket_last_error()).PHP_EOL);
    }
    socket_write($socket,$header);
    $data = json_encode($data);
   // print_r($data);
    sleep(2);
// - - - - - - - - - - - - - - - - - -  --  - - - -
$final=true;
$header=chr(($final?0x80:0) | 0x01);
//$header=chr(($final?0x80:0) | 0x02); // 0x02 binary mode
    // Mask 0x80 | payload length (0-125)
    if(strlen($data)<126) $header.=chr(0x80 | strlen($data));
    elseif (strlen($data)<0xFFFF) $header.=chr(0x80 | 126) . pack("n",strlen($data));
    else $header.=chr(0x80 | 127) . pack("N",0) . pack("N",strlen($data));
  
    // Add mask
    $mask=pack("N",rand(1,0x7FFFFFFF));
    $header.=$mask;
  
    // Mask application data.
    for($i = 0; $i < strlen($data); $i++)
      $data[$i]=chr(ord($data[$i]) ^ ord($mask[$i % 4]));


    //socket_write($socket,$data,strlen($data)) or die('haha');
    echo $header.$data;
    socket_write($socket,$header.$data) or die('haha');
   $str =  socket_strerror(socket_last_error($socket)) ;
   echo $str;
    /*
    $fp = stream_socket_client("tcp://$host:2000", $errno, $errstr, 30);
  if (!$fp) {
      echo "$errstr ($errno)<br />\n";
  } else {
      fwrite($fp, "$header");
      while (!feof($fp)) {
          echo fgets($fp, 1024);
      }
      fclose($fp);
  }
        */
    }
    
}


?>