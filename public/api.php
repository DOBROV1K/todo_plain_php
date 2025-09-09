<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use Src\Model\Task;
use Src\Logger\Logger;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$logger = Logger::getInstance();
$taskModel = new Task();

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $filter = $_GET['filter'] ?? null;
        $tasks = $taskModel->getAll($filter);
        echo json_encode($tasks);
        exit;
    }

    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);
        $title = trim($body['title'] ?? '');
        if ($title === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Title required']);
            exit;
        }
        $taskModel->add($title);
        $tasks = $taskModel->getAll(null);
        echo json_encode($tasks[0] ?? null);
        exit;
    }

    if ($method === 'PUT') {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid id']);
            exit;
        }
        $taskModel->toggle($id);
        $tasks = $taskModel->getAll(null);
        foreach ($tasks as $t) {
            if ((int)$t['id'] === $id) {
                echo json_encode($t);
                exit;
            }
        }
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        exit;
    }

    if ($method === 'DELETE') {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid id']);
            exit;
        }
        $taskModel->delete($id);
        echo json_encode(['ok' => true]);
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
} catch (Throwable $e) {
    $logger->write("API ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
