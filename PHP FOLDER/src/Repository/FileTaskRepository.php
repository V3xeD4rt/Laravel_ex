<?php

namespace App\Repository;

use App\Model\Task;

class FileTaskRepository implements TaskRepositoryInterface
{   
    public string $filepath;
    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
    }
    
    public function findAll(): array
{
    if (!file_exists($this->filepath)) {
        return [];
    }
    $content = file_get_contents($this->filepath);
    if ($content === false || trim($content) === '') {
        return [];
    }
    $data = json_decode($content, true);
    if (!is_array($data)) {
        return [];
    }
    $tasks = [];
    foreach ($data as $item) {
        // Skip invalid or empty task items
        if (!is_array($item) || empty($item['title']) || !isset($item['id'])) {
            continue;
        }
        
        $tasks[] = new Task(
            $item['title'] ?? '',
            $item['completed'] ?? false,
            $item['id'] ?? 0  // Use 0 as default instead of null
        );
    }
    
    usort($tasks, function($a, $b) {
        return $b->getId() - $a->getId();
    });
    
    return $tasks;
}
    
    public function add($task): void
{ 
    $tasks = $this->findAll();
    
    $maxId = 0;
    foreach ($tasks as $existingTask) {
        if ($existingTask->getId() > $maxId) {
            $maxId = $existingTask->getId();
        }
    }
    
    // Create a new Task object with the new ID
    $newTask = new Task(
        $task->getTitle(),
        $task->isCompleted(),
        $maxId + 1
    );
    
    // Add the Task object to the array
    array_unshift($tasks, $newTask);
    
    // Convert all Task objects to arrays for JSON storage
    $data = [];
    foreach ($tasks as $taskObj) {
        $data[] = [
            'id' => $taskObj->getId(),
            'title' => $taskObj->getTitle(),
            'completed' => $taskObj->isCompleted()
        ];
    }
    
    file_put_contents($this->filepath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
    
    public function toggle(int $taskId): void
    {
        $tasks = $this->findAll();
        $data = [];
        
        foreach ($tasks as $task) {
            $item = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'completed' => $task->isCompleted()
            ];
            
            if ($task->getId() == $taskId) {
                $item['completed'] = !$task->isCompleted();
            }
            
            $data[] = $item;
        }
        
        file_put_contents($this->filepath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
    
    public function delete(int $taskId): void
    {
        $tasks = $this->findAll();
        $data = [];
        
        foreach ($tasks as $task) {
            if ($task->getId() == $taskId) {
                continue;
            }
            
            $data[] = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'completed' => $task->isCompleted()
            ];
        }
        
        file_put_contents($this->filepath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}