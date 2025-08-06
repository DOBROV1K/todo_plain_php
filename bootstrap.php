<?php

use Src\Logger\Logger;

require_once __DIR__ . '/vendor/autoload.php';

$logger = Logger::getInstance();


$method = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
$uri = $_SERVER['REQUEST_URI'] ?? 'N/A';
$body = file_get_contents('php://input');
$logger->write("REQUEST: $method $uri | BODY: $body");


set_exception_handler(function (Throwable $e) use ($logger) {
    $logger->write("EXCEPTION: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}");
    http_response_code(500);
    echo "Internal Server Error";
    exit;
});
