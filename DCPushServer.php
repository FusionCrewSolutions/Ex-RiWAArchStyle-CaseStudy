<?php
/*
No ZMQ support for php > 7.2

Not working in XAMPP 7.2, Apache gives and error when creating socket for ZMQ

Works well in XAMPP 5.6.38

Download the matching version (phpver and arch) - http: //pecl.php.net/package/zmq

Copy php_zmq.dll to XAMPP/php/ext/
Copy libzmq.dll to XAMPP/php/

Add - extension=php_zmq.dll - to php.ini
Add - LoadFile "FULL\PATH\XAMPP\php\libzmq.dll" - to httpd.conf

*/


use ExRiWAArch\DCPushSender;

require 'vendor\autoload.php';
require 'DCPushSender.php';

$loop = React\EventLoop\Factory::create();
$DCPushSender = new DCPushSender;

//ROW SOCKET ====================================================
// Listen to the web server's ZMQ push request
$context = new React\ZMQ\Context($loop);
$pull = $context->getSocket(ZMQ::SOCKET_PULL);
$pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
$pull->on('message', array($DCPushSender, 'onWebServerCall'));

//WEBSOCKET ====================================================
// Set up a WebSocket server
$webSock = new React\Socket\Server('0.0.0.0:8080', $loop); // Binding to 0.0.0.0 means remotes can connect
$webServer = new Ratchet\Server\IoServer(
    new Ratchet\Http\HttpServer(
        new Ratchet\WebSocket\WsServer(
            new Ratchet\Wamp\WampServer(
                $DCPushSender
            )
        )
    ),
    $webSock
);

$loop->run();

?>