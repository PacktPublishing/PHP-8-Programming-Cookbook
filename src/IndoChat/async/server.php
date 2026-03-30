<?php
require __DIR__ . '/../config/config.php';
use Cookbook\IndoChat\ChatServer;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
$server = IoServer::factory(
    new HttpServer(new WsServer(new ChatServer(USERS_FILE, API_CALLBACK))),
    WS_PORT,
    WS_HOST
);
$server->run();
