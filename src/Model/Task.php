<?php
namespace Src\Model;

require_once __DIR__ . '/Database.php';

class Task
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function add(string $title): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO tasks (title, status) VALUES (:title, "pending")');
        $stmt->execute(['title' => $title]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM tasks WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function toggle(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE tasks SET status = IF(status = "pending", "ready", "pending") WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function getAll(?string $filter = null): array
    {
        $sql = 'SELECT * FROM tasks';
        if ($filter === 'done') {
            $sql .= ' WHERE status = "ready"';
        } elseif ($filter === 'pending') {
            $sql .= ' WHERE status = "pending"';
        }
        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}
