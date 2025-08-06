<?php

namespace Src\Model;

require_once __DIR__ . '/Database.php';

use Doctrine\DBAL\Connection;
use Src\Logger\Logger;

class Task
{
    private Connection $conn;
    private string $table = 'tasks';

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function add(string $title): void
    {
        $logger = Logger::getInstance();
        $logger->write("DB OPERATION: INSERT INTO {$this->table} title='$title'");

        $this->conn->insert($this->table, [
            'title'  => $title,
            'status' => 'pending',
        ]);
    }

    public function delete(int $id): void
    {
        $logger = Logger::getInstance();
        $logger->write("DB OPERATION: DELETE FROM {$this->table} WHERE id=$id");

        $this->conn->delete($this->table, ['id' => $id]);
    }

    public function toggle(int $id): void
    {
        $logger = Logger::getInstance();

        $sql = "SELECT status FROM {$this->table} WHERE id = ?";
        $logger->write("DB OPERATION: SELECT status FROM {$this->table} WHERE id=$id");

        $row = $this->conn->fetchOne($sql, [$id]);

        if ($row === false) {
            $logger->write("DB RESULT: Task with id=$id not found");
            return;
        }

        $newStatus = ($row === 'pending') ? 'ready' : 'pending';
        $logger->write("DB OPERATION: UPDATE {$this->table} SET status='$newStatus' WHERE id=$id");

        $this->conn->update($this->table, ['status' => $newStatus], ['id' => $id]);
    }

    /**
     * @param string|null $filter 'done' | 'pending' | null
     * @return array<int,array<string,mixed>>
     */
    public function getAll(?string $filter = null): array
    {
        $logger = Logger::getInstance();

        $sql = "SELECT id, title, status FROM {$this->table}";
        $params = [];

        if ($filter === 'done') {
            $sql .= " WHERE status = ?";
            $params[] = 'ready';
        } elseif ($filter === 'pending') {
            $sql .= " WHERE status = ?";
            $params[] = 'pending';
        }

        $sql .= " ORDER BY id DESC";

        $logger->write("DB OPERATION: $sql | PARAMS: " . json_encode($params));

        return $this->conn->fetchAllAssociative($sql, $params);
    }
}
