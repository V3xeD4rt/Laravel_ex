<?php

namespace App\Controller;

use App\Model\Task;
use App\Repository\TaskRepositoryInterface;

class TaskController {

    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository){
        $this->repository = $repository;
    }

    public function list() {
        $tasks = $this->repository->findAll();
        require __DIR__ . '/../View/task_list.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            if (!empty($title)) {
                $task = new Task($title);
                $this->repository->add($task);
                header('Location: ?route=task/list');
                exit;
            }
        }
        require __DIR__ . '/../View/task_add.php';
    }

    public function toggle() {
        $taskId = $_GET['id'] ?? null;
        if ($taskId) {
            $this->repository->toggle($taskId);
        }
        header('Location: ?route=task/list');
        exit;
    }

    public function delete() {
        $taskId = $_GET['id'] ?? null;
        if ($taskId) {
            $this->repository->delete($taskId);
        }
        header('Location: ?route=task/list');
        exit;
    }

    public function switchMode() {
        $mode = $_GET['mode'] ?? 'mysql';
        if (in_array($mode, ['mysql', 'file'])) {
            $_SESSION['repository_mode'] = $mode;
        }
        header('Location: ?route=task/list');
        exit;
    }
}