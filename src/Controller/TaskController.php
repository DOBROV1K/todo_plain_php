<?php

namespace Src\Controller;

use Src\Model\Task;
use Src\Logger\Logger;

class TaskController
{
    private Task $task;

    public function __construct()
    {
        $this->task = new Task();
    }

    public function handleRequest(): void
    {
        $logger = Logger::getInstance();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $logger->write("ROUTE: Handling $method $uri");

        if ($method === 'POST' && isset($_POST['description'])) {
            $description = (string) $_POST['description'];
            $logger->write("ACTION: Adding task with description='$description'");
            $this->task->add($description);
            header('Location: index.php');
            exit;
        }

        if (isset($_GET['action'], $_GET['id'])) {
            $action = $_GET['action'];
            $id = (int)$_GET['id'];

            $logger->write("ACTION: $action on task ID=$id");

            if ($action === 'toggle') {
                $this->task->toggle($id);
            } elseif ($action === 'delete') {
                $this->task->delete($id);
            }

            header('Location: index.php');
            exit;
        }

        $filter = $_GET['filter'] ?? null;
        $logger->write("ACTION: Fetching tasks with filter='" . ($filter ?? 'none') . "'");

        $tasks = $this->task->getAll($filter);

        include __DIR__ . '/../View/tasks.php';
    }
}

