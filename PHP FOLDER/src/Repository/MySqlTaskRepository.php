<?php

namespace App\Repository;

use App\Model\Task;
use PDO;

class MySqlTaskRepository implements TaskRepositoryInterface
{
    private $pdo;
    
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec("SET NAMES utf8mb4");
    }
    
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, title, completed FROM tasks ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tasks = [];
        
        foreach ($rows as $row) {
            $tasks[] = new Task($row['title'], (bool)$row['completed'], $row['id']);
        }    
        
        return $tasks;
    }
    
    public function add(Task $task): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO tasks (title, completed) VALUES (:title, :completed)');
        $stmt->execute([
            ':title' => $task->getTitle(),
            ':completed' => $task->isCompleted() ? 1 : 0,
        ]);
    }
    
    public function toggle(int $taskId): void
    {
        $stmt = $this->pdo->prepare("SELECT completed FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $taskId]);
        $currentStatus = $stmt->fetchColumn();
        
        $newStatus = $currentStatus ? 0 : 1;
        
        $stmt = $this->pdo->prepare("UPDATE tasks SET completed = :completed WHERE id = :id");
        $stmt->execute([
            ':completed' => $newStatus,
            ':id' => $taskId
        ]);
    }
    
    public function delete(int $taskId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $taskId]);
    }
}