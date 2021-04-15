<?php

require_once __DIR__ . "/../vendor/autoload.php";
// this handler will echo each message to standard output
$client = new \vakata\websocket\Client('wss://127.0.0.1:9002');
$client->onMessage(function ($message, $client) {
    echo $message . "\r\n";
});
$client->send("hey");
$client->run();

