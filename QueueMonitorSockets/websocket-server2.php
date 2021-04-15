<?php


$app = new class implements \Wrench\Application\DataHandlerInterface
{
    public function onData(string $data, \Wrench\Connection $connection): void
    {
        $connection->send($data);
    }
};

// A websocket server, listening on port 8000
$server = new \Wrench\BasicServer('ws://localhost:8000', array(
    'allowed_origins' => array(
        'mysite.com',
        'mysite.dev.localdomain'
    )
));

$server->registerApplication('echo', $app);
$server->registerApplication('chat', new \My\ChatApplication());
$server->setLogger($monolog); // PSR3
$server->run();