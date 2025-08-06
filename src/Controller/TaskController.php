<?php

namespace Src\Controller;

use Src\Model\Task;

class TaskController
{
    private Task $task;

    public function __construct()
    {
        $this->task = new Task();
    }

    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['description'])) {
            $this->task->add($_POST['description']);
            header('Location: index.php');
            exit;
        }

        if (isset($_GET['action'], $_GET['id'])) {
            $action = $_GET['action'];
            $id = (int)$_GET['id'];
            if ($action === 'toggle') {
                $this->task->toggle($id);
            } elseif ($action === 'delete') {
                $this->task->delete($id);
            }
            header('Location: index.php');
            exit;
        }

        $filter = $_GET['filter'] ?? null;
        $tasks = $this->task->getAll($filter);

        include __DIR__ . '/../View/tasks.php';
    }
}

