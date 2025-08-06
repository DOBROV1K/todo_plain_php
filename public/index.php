<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use Src\Controller\TaskController;

$controller = new TaskController();
$controller->handleRequest();

