<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/utils/socket_communication.php";
require_once __DIR__ . "/utils/asterisk_monitor.php";

/*

// PARAMETERS
$IP = "127.0.0.1";
$PORT = 9002;
$local_cert = '/etc/apache2/certificate/apache-certificate.pem';
$local_pk = '/etc/apache2/certificate/apache-key.pem';
$passphrase = '';
$allow_self_signed = true;
$IP = "172.21.21.248";
$PORT = 9002;
$local_cert = '/etc/httpd/certs/ridetel-full.pem';
$local_pk = '/etc/httpd/certs/ridetel-key.pem';
$passphrase = '';
$allow_self_signed = true;
*/
$IP = "127.0.0.1";
$PORT = 9002;

$local_cert = '/var/www/html/CallCenterMonitor/QueueMonitorSockets/combined.pem';
$passphrase = '';
$allow_self_signed = true;

$string_conn = "tcp://$IP:$PORT";
$loop = React\EventLoop\Factory::create();

$socket = new React\Socket\Server($string_conn, $loop, array(
   'tls' => array(
       'verify_peer' => false,
       'allow_self_signed' => $allow_self_signed,
       'passphrase' => $passphrase,
       'local_cert' => $local_cert
       , 'ciphers' => 'TLS_AES_128_GCM_SHA256'  // TLS_AES_128_GCM_SHA256,
)
));

$connections = array();

$socket->on('connection', function (React\Socket\ConnectionInterface $connection) use(&$connections){

    echo "\nNew connection detected, id: " . $connection->getRemoteAddress() . "\n";
    
    // var_dump($connection);

    $connection->on('end', function () {
        echo '\nended';
    });
    
    $connection->on('error', function (Exception $e) {
        echo '\nerror: ' . $e->getMessage();
    });
    
    $connection->on('close', function () {
        echo '\nclosed';
    });
    
    $connection->on('data', function ($data) use ($connection, &$connections) {
        
        $socket_key = getSocketKey($data);
        if($socket_key != "null"){
            echo "\nReceived socket connection request\n$data";

            $socket_accept_code = base64_encode(sha1($socket_key . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true));
            $response = "HTTP/1.1 101 Switching Protocols\r\nUpgrade: websocket\r\nConnection: Upgrade\r\nSec-WebSocket-Accept: $socket_accept_code\r\n\r\n";
            
            $id = $connection->getRemoteAddress();
            
            echo "\nAllowing communication by websocket to $id\n";
            $connection->write($response);

            $connections[$id] = $connection;
            echo "\nSaving suscriptor. Actual suscriptors: " . count($connections);

        }
        else{
          
            $raw_message = decodeInputMessage($data);
            // echo "Raw: " . $raw_message;

            $message = json_decode($raw_message);
            $type = $message->type;
            switch ($type) {

                case 'close':
                    echo "\nClosing connection with client\n";
                    $connection->close();
                    break;

                case 'update':
                    echo "\nUpdating\n";
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
                    echo "\nDefault, Received: $raw_message";

            }

            

            
        }
        
    });
});

$socket->on('error', function (Exception $e) {
    echo '\nError' . $e->getMessage() . PHP_EOL;
});


echo "\nLISTENING on $string_conn\n";

$loop->addPeriodicTimer(1, function(React\EventLoop\Timer\Timer $timer) use($loop, &$connections) {
   
    if(count($connections) > 0){

        // $data = queryInfoQueue();
        $data = getTestMemcacheData();
        foreach($connections as $connection_id => $connection) {
            // echo "\nUpdating suscriptor: $connection_id";

            $connection->write(encodeOutputMessage(
                json_encode(array(
                    "type"=>"update","msg"=>"updating_your_dear_suscriptor", "data"=> $data // getMemcacheData()  getTestMemcacheData()
                ))
            ));
        }
    }
    

    //  $loop->cancelTimer($timer);

});

$loop->run();

function getTestMemcacheData(){
    return [
        "5000" => [
            "cola" => "5000 has 0 calls (max unlimited) in 'random' strategy (0s holdtime, 0s talktime), W => 0, C => 0, A => 0, SL => 0.0% within 60s",
            "nomcola" => "5000",
            "llamencola" => "0",
            "llamcontestadas" => "0",
            "llamabandonadas" => "0",
            "niveldeservicio" => "0.0% within 60s",
            "agentes" => [
               [
                  "linea" => "Agente 1 (Local\/401@from-internal) (ringinuse enabled) (dynamic) (Not in use) has taken no calls yet",
                  "miembro" => "5000",
                  "llamcontestadas" => 0,
                  "nombre" => "Agente 1 ",
                  "canal" => "Local\/401@from-internal",
                  "estado" => "Libre"
               ]
            ],
            "llamadas" => [
               
            ],
            "llamactuales" => 0
         ],
         "5001" => [
            "cola" => "5001 has 0 calls (max unlimited) in 'ringall' strategy (9s holdtime, 211s talktime), W => 0, C => 2, A => 0, SL => 100.0% within 30s",
            "nomcola" => "5001",
            "llamencola" => "19",
            "llamcontestadas" => "2",
            "llamabandonadas" => "0",
            "niveldeservicio" => "100.0% within 30s",
            "agentes" => [
               [
                  "linea" => "Stalin (SIP\/303 from hint => 303@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
                  "miembro" => "5001",
                  "llamcontestadas" => 0,
                  "nombre" => "Stalin ",
                  "canal" => "SIP\/303",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "Eliecer (SIP\/302 from hint => 302@ext-local) (ringinuse disabled) (Not in use) has taken 3 calls (last was 7761 secs ago)",
                  "miembro" => "5001",
                  "llamcontestadas" => "3",
                  "nombre" => "Eliecer ",
                  "canal" => "SIP\/302",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "Andres (SIP\/301 from hint => 301@ext-local) (ringinuse disabled) (Not in use) has taken 1 calls (last was 894 secs ago)",
                  "miembro" => "5001",
                  "llamcontestadas" => "1",
                  "nombre" => "Andres ",
                  "canal" => "SIP\/301",
                  "estado" => "Conectado",
                  "callerid" => 12315534,
                  "duracion" => rand(1, 60)
               ],
               [
                  "linea" => "CompuStalin (IAX2\/504 from hint => 504@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5001",
                  "llamcontestadas" => 0,
                  "nombre" => "CompuStalin ",
                  "canal" => "IAX2\/504",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Eliecer IAX (IAX2\/502 from hint => 502@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5001",
                  "llamcontestadas" => 0,
                  "nombre" => "Eliecer IAX ",
                  "canal" => "IAX2\/502",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Stalin IAX (IAX2\/501 from hint => 501@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5001",
                  "llamcontestadas" => 0,
                  "nombre" => "Stalin IAX ",
                  "canal" => "IAX2\/501",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Andres IAX (IAX2\/500 from hint => 500@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5001",
                  "llamcontestadas" => 0,
                  "nombre" => "Andres IAX ",
                  "canal" => "IAX2\/500",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "SA Tel (SIP\/313 from hint => 313@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
                  "miembro" => "5001",
                  "llamcontestadas" => 0,
                  "nombre" => "SA Tel ",
                  "canal" => "SIP\/313",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "ET Tel (SIP\/312 from hint => 312@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
                  "miembro" => "5001",
                  "llamcontestadas" => 0,
                  "nombre" => "ET Tel ",
                  "canal" => "SIP\/312",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "AG Tel (SIP\/311 from hint => 311@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5001",
                  "llamcontestadas" => 0,
                  "nombre" => "AG Tel ",
                  "canal" => "SIP\/311",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Eliecer Tates (SIP\/304 from hint => 304@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5001",
                  "llamcontestadas" => 0,
                  "nombre" => "Eliecer Tates ",
                  "canal" => "SIP\/304",
                  "estado" => "Offline"
               ]
            ],
            "llamadas" => [
                [
                    "callerid" => 1231425,
                    "canal" => "SIP\/304",
                    "tespera" => rand(1, 100),

                ]
            ],
            "llamactuales" => 1
         ],
         "5002" => [
            "cola" => "5002 has 0 calls (max unlimited) in 'ringall' strategy (0s holdtime, 0s talktime), W => 0, C => 0, A => 0, SL => 0.0% within 60s",
            "nomcola" => "5002",
            "llamencola" => "0",
            "llamcontestadas" => "0",
            "llamabandonadas" => "0",
            "niveldeservicio" => "0.0% within 60s",
            "agentes" => [
               [
                  "linea" => "Stalin (SIP\/303 from hint => 303@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
                  "miembro" => "5002",
                  "llamcontestadas" => 0,
                  "nombre" => "Stalin ",
                  "canal" => "SIP\/303",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "Eliecer (SIP\/302 from hint => 302@ext-local) (ringinuse disabled) (Not in use) has taken 3 calls (last was 7761 secs ago)",
                  "miembro" => "5002",
                  "llamcontestadas" => "3",
                  "nombre" => "Eliecer ",
                  "canal" => "SIP\/302",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "Andres (SIP\/301 from hint => 301@ext-local) (ringinuse disabled) (Not in use) has taken 1 calls (last was 894 secs ago)",
                  "miembro" => "5002",
                  "llamcontestadas" => "1",
                  "nombre" => "Andres ",
                  "canal" => "SIP\/301",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "CompuStalin (IAX2\/504 from hint => 504@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5002",
                  "llamcontestadas" => 0,
                  "nombre" => "CompuStalin ",
                  "canal" => "IAX2\/504",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Eliecer IAX (IAX2\/502 from hint => 502@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5002",
                  "llamcontestadas" => 0,
                  "nombre" => "Eliecer IAX ",
                  "canal" => "IAX2\/502",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Stalin IAX (IAX2\/501 from hint => 501@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5002",
                  "llamcontestadas" => 0,
                  "nombre" => "Stalin IAX ",
                  "canal" => "IAX2\/501",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Andres IAX (IAX2\/500 from hint => 500@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5002",
                  "llamcontestadas" => 0,
                  "nombre" => "Andres IAX ",
                  "canal" => "IAX2\/500",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "SA Tel (SIP\/313 from hint => 313@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
                  "miembro" => "5002",
                  "llamcontestadas" => 0,
                  "nombre" => "SA Tel ",
                  "canal" => "SIP\/313",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "ET Tel (SIP\/312 from hint => 312@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
                  "miembro" => "5002",
                  "llamcontestadas" => 0,
                  "nombre" => "ET Tel ",
                  "canal" => "SIP\/312",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "AG Tel (SIP\/311 from hint => 311@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5002",
                  "llamcontestadas" => 0,
                  "nombre" => "AG Tel ",
                  "canal" => "SIP\/311",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Eliecer Tates (SIP\/304 from hint => 304@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5002",
                  "llamcontestadas" => 0,
                  "nombre" => "Eliecer Tates ",
                  "canal" => "SIP\/304",
                  "estado" => "Offline"
               ]
            ],
            "llamadas" => [
               
            ],
            "llamactuales" => 0
         ],
         "5003" => [
            "cola" => "5003 has 0 calls (max unlimited) in 'ringall' strategy (6s holdtime, 565s talktime), W => 0, C => 2, A => 0, SL => 100.0% within 60s",
            "nomcola" => "5003",
            "llamencola" => "0",
            "llamcontestadas" => "2",
            "llamabandonadas" => "0",
            "niveldeservicio" => "100.0% within 60s",
            "agentes" => [
               [
                  "linea" => "Stalin (SIP\/303 from hint => 303@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
                  "miembro" => "5003",
                  "llamcontestadas" => 0,
                  "nombre" => "Stalin ",
                  "canal" => "SIP\/303",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "Eliecer (SIP\/302 from hint => 302@ext-local) (ringinuse disabled) (Not in use) has taken 3 calls (last was 7761 secs ago)",
                  "miembro" => "5003",
                  "llamcontestadas" => "3",
                  "nombre" => "Eliecer ",
                  "canal" => "SIP\/302",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "Andres (SIP\/301 from hint => 301@ext-local) (ringinuse disabled) (Not in use) has taken 1 calls (last was 894 secs ago)",
                  "miembro" => "5003",
                  "llamcontestadas" => "1",
                  "nombre" => "Andres ",
                  "canal" => "SIP\/301",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "CompuStalin (IAX2\/504 from hint => 504@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5003",
                  "llamcontestadas" => 0,
                  "nombre" => "CompuStalin ",
                  "canal" => "IAX2\/504",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Eliecer IAX (IAX2\/502 from hint => 502@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5003",
                  "llamcontestadas" => 0,
                  "nombre" => "Eliecer IAX ",
                  "canal" => "IAX2\/502",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Stalin IAX (IAX2\/501 from hint => 501@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5003",
                  "llamcontestadas" => 0,
                  "nombre" => "Stalin IAX ",
                  "canal" => "IAX2\/501",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Andres IAX (IAX2\/500 from hint => 500@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5003",
                  "llamcontestadas" => 0,
                  "nombre" => "Andres IAX ",
                  "canal" => "IAX2\/500",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "SA Tel (SIP\/313 from hint => 313@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
                  "miembro" => "5003",
                  "llamcontestadas" => 0,
                  "nombre" => "SA Tel ",
                  "canal" => "SIP\/313",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "ET Tel (SIP\/312 from hint => 312@ext-local) (ringinuse disabled) (Not in use) has taken no calls yet",
                  "miembro" => "5003",
                  "llamcontestadas" => 0,
                  "nombre" => "ET Tel ",
                  "canal" => "SIP\/312",
                  "estado" => "Libre"
               ],
               [
                  "linea" => "AG Tel (SIP\/311 from hint => 311@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5003",
                  "llamcontestadas" => 0,
                  "nombre" => "AG Tel ",
                  "canal" => "SIP\/311",
                  "estado" => "Offline"
               ],
               [
                  "linea" => "Eliecer Tates (SIP\/304 from hint => 304@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "5003",
                  "llamcontestadas" => 0,
                  "nombre" => "Eliecer Tates ",
                  "canal" => "SIP\/304",
                  "estado" => "Offline"
               ]
            ],
            "llamadas" => [
               
            ],
            "llamactuales" => 0
         ],
         "10001" => [
            "cola" => "10001 has 0 calls (max unlimited) in 'ringall' strategy (0s holdtime, 0s talktime), W => 0, C => 0, A => 0, SL => 0.0% within 60s",
            "nomcola" => "10001",
            "llamencola" => "0",
            "llamcontestadas" => "0",
            "llamabandonadas" => "0",
            "niveldeservicio" => "0.0% within 60s",
            "agentes" => [
               [
                  "linea" => "Eliecer Tates (SIP\/304 from hint => 304@ext-local) (ringinuse disabled) (Unavailable) has taken no calls yet",
                  "miembro" => "10001",
                  "llamcontestadas" => 0,
                  "nombre" => "Eliecer Tates ",
                  "canal" => "SIP\/304",
                  "estado" => "Offline"
               ]
            ],
            "llamadas" => [
               
            ],
            "llamactuales" => 0
         ]

    ];
}

