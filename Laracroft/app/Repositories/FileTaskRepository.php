<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class FileTaskRepository implements TaskRepositoryInterface
{
    private string $filepath;

    public function __construct()
    {
        $this->filepath = 'tasks.json';
        
        // Create file if it doesn't exist
        if (!Storage::exists($this->filepath)) {
            Storage::put($this->filepath, json_encode([], JSON_PRETTY_PRINT));
        }
    }
    
    public function findAll(): array
    {
        if (!Storage::exists($this->filepath)) {
            return [];
        }
        
        $content = Storage::get($this->filepath);
        if ($content === false || trim($content) === '') {
            return [];
        }
        
        $data = json_decode($content, true);
        if (!is_array($data)) {
            return [];
        }
        
        $tasks = [];
        foreach ($data as $item) {
            if (!is_array($item) || empty($item['title']) || !isset($item['id'])) {
                continue;
            }
            
            $tasks[] = new Task(
                $item['title'] ?? '',
                $item['completed'] ?? false,
                $item['id'] ?? 0
            );
        }
        
        usort($tasks, function($a, $b) {
            return $b->getId() - $a->getId();
        });
        
        return $tasks;
    }
    
    public function add(Task $task): void
    {
        $tasks = $this->findAll();
        
        $maxId = 0;
        foreach ($tasks as $existingTask) {
            if ($existingTask->getId() > $maxId) {
                $maxId = $existingTask->getId();
            }
        }
        
        $newTask = new Task(
            $task->getTitle(),
            $task->isCompleted(),
            $maxId + 1
        );
        
        array_unshift($tasks, $newTask);
        
        $data = [];
        foreach ($tasks as $taskObj) {
            $data[] = [
                'id' => $taskObj->getId(),
                'title' => $taskObj->getTitle(),
                'completed' => $taskObj->isCompleted()
            ];
        }
        
        Storage::put($this->filepath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
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
        
        Storage::put($this->filepath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
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
        
        Storage::put($this->filepath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}