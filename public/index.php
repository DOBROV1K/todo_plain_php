<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Src\Controller\TaskController;

$controller = new TaskController();
$controller->handleRequest();

