<?php

namespace App\Repository;

use App\Model\Task;

interface TaskRepositoryInterface
{
    public function findAll(): array;
    public function add(Task $task): void;
    public function toggle(int $taskId): void;
    public function delete(int $taskId): void;
}