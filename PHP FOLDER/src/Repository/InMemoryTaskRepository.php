<?php

namespace App\Repository;

use App\Model\Task;

class InMemoryTaskRepository implements TaskRepositoryInterface
{
    private array $tasks = [];

    public function __construct() {
        $this->tasks = [
            new Task("Купить кофе", false, 3),
            new Task("Проспать пары", false, 2),
            new Task("Опоздать на пары", false, 1)
        ];
        
        usort($this->tasks, function($a, $b) {
            return $b->getId() - $a->getId();
        });
    }

    public function findAll(): array {
        return $this->tasks;
    }
    
    public function add($task): void { 
        $newId = count($this->tasks) + 1;
        $newTask = new Task($task->getTitle(), false, $newId);
        array_unshift($this->tasks, $newTask);
    }
    
    public function toggle(int $taskId): void {
        foreach ($this->tasks as $task) {
            if ($task->getId() == $taskId) {
                $task->setCompleted(!$task->isCompleted());
                break;
            }
        }
    }
    
    public function delete(int $taskId): void {
        $this->tasks = array_filter($this->tasks, function($task) use ($taskId) {
            return $task->getId() != $taskId;
        });
    }
}