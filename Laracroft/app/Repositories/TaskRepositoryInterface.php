<?php

namespace App\Repositories;

use App\Models\Task;

interface TaskRepositoryInterface
{
    /**
     * Get all tasks
     */
    public function findAll(): array;

    /**
     * Add a new task
     */
    public function add(Task $task): void;

    /**
     * Toggle task completion status
     */
    public function toggle(int $taskId): void;

    /**
     * Delete a task
     */
    public function delete(int $taskId): void;
}