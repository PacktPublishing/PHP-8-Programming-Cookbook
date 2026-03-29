<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';
require __DIR__ . '/ChatServer.php';
use Cookbook\REST\ChatServer;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
$server = IoServer::factory(
    new HttpServer(new WsServer(new ChatServer(USERS_FILE))),
    WS_PORT,
    WS_HOST
);
$server->run();
