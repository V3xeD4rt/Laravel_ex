<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

class MySqlTaskRepository implements TaskRepositoryInterface
{
    public function findAll(): array
    {
        $rows = DB::table('tasks')
                 ->select('id', 'title', 'completed')
                 ->orderBy('id', 'desc')
                 ->get();
        
        $tasks = [];
        
        foreach ($rows as $row) {
            $tasks[] = new Task(
                $row->title,
                (bool)$row->completed,
                $row->id
            );
        }
        
        return $tasks;
    }
    
    public function add(Task $task): void
    {
        DB::table('tasks')->insert([
            'title' => $task->getTitle(),
            'completed' => $task->isCompleted() ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    public function toggle(int $taskId): void
    {
        $current = DB::table('tasks')
                    ->where('id', $taskId)
                    ->select('completed')
                    ->first();
                    
        if ($current) {
            $newStatus = $current->completed ? 0 : 1;
            
            DB::table('tasks')
                ->where('id', $taskId)
                ->update([
                    'completed' => $newStatus,
                    'updated_at' => now()
                ]);
        }
    }
    
    public function delete(int $taskId): void
    {
        DB::table('tasks')->where('id', $taskId)->delete();
    }
}