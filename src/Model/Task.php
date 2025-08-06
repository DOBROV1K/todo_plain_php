<?php
namespace Src\Model;

require_once __DIR__ . '/Database.php';

use Doctrine\DBAL\Connection;

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
        $this->conn->insert($this->table, [
            'title'      => $title,
            'status'     => 'pending',
        ]);
    }

    public function delete(int $id): void
    {
        $this->conn->delete($this->table, ['id' => $id]);
    }

    public function toggle(int $id): void
    {
        $sql = "SELECT status FROM {$this->table} WHERE id = ?";
        $row = $this->conn->fetchOne($sql, [$id]);

        if ($row === false) {
            return;
        }

        $newStatus = ($row === 'pending') ? 'ready' : 'pending';
        $this->conn->update($this->table, ['status' => $newStatus], ['id' => $id]);
    }

    /**
     * @param string|null $filter 'done' | 'pending' | null
     * @return array<int,array<string,mixed>>
     */
    public function getAll(?string $filter = null): array
    {
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

        return $this->conn->fetchAllAssociative($sql, $params);
    }
}
