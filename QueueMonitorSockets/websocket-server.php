<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/utils/socket_communication.php";

// PARAMETERS
$IP = "127.0.0.1";
$PORT = 9002;
$local_cert = '/var/www/ssl/certificate.self_signed.pem';
$local_pk = '/var/www/ssl/key.pem';
$passphrase = '';
$allow_self_signed = true;



$mc = new Memcached();
$mc->addServer('localhost', 11211) or die ("Unable to connect");

function getMemcacheData(){
    return $mc->get('infocolas');
}


$string_conn = "tcp://$IP:$PORT";
$loop = React\EventLoop\Factory::create();

$socket = new React\Socket\Server($string_conn, $loop, array(
    'tls' => array(
        'verify_peer' => false,
        'allow_self_signed' => $allow_self_signed,
        'passphrase' => $passphrase,
        'local_cert' => $local_cert,
        'local_pk' => $local_pk,
        'ciphers' => 'ECDHE_RSA_WITH_AES_128_GCM_SHA256'
 )
));


$socket->on('connection', function (React\Socket\ConnectionInterface $connection) {

    echo "\nNew connection detected\n";
   
    
    $connection->on('data', function ($data) use ($connection) {
        
        $socket_key = getSocketKey($data);
        if($socket_key != "null"){
            echo "\nReceived socket connection request\n$data";

            $socket_accept_code = base64_encode(sha1($socket_key . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true));
            $response = "HTTP/1.1 101 Switching Protocols\r\nUpgrade: websocket\r\nConnection: Upgrade\r\nSec-WebSocket-Accept: $socket_accept_code\r\n\r\n";
            echo "\nAllowing communication by websocket\n";
            $connection->write($response);
        }
        else{
          
            $raw_message = decodeInputMessage($data);
            echo "\nReceived: $raw_message";

            $message = json_decode($raw_message);
            $type = $message->type;
            switch ($type) {

                case 'update':
                    $connection->write(encodeOutputMessage(
                        json_encode(array(
                            "type"=>$type,"msg"=>"updating_clients", "data"=> getTestMemcacheData() // getMemcacheData()  getTestMemcacheData()
                        ))
                    ));
                    break;

                default:
                    $connection->write(encodeOutputMessage(
                        json_encode(array(
                            "type"=>$type,"msg"=>"El tipo de mensaje no es admitido", "data"=> []
                        ))
                    ));

            }

            
        }
        // $connection->close();
    });
});

$socket->on('error', function (Exception $e) {
    echo '\nError' . $e->getMessage() . PHP_EOL;
});


echo "\nLISTENING on $string_conn\n";
$loop->run();


function getTestMemcacheData(){
    return [
        ];
}

