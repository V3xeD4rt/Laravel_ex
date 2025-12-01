<?php

namespace App\Container;

use App\Repository\{TaskRepositoryInterface,FileTaskRepository,InMemoryTaskRepository,MySqlTaskRepository};
use Exception;
use PDO;

class Container {
    private array $definitions = [];
    private array $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function get(string $id): mixed {
        if (!isset($this->definitions[$id])) {
            $this->definitions[$id] = $this->create($id);
        }
        return $this->definitions[$id];
    }

    private function create(string $id) {
        switch ($id) {
            case PDO::class:
                $db = $this->config['db'];
                return new PDO($db['dsn'], $db['user'], $db['pass'], $db['options'] ?? []);
            
            case FileTaskRepository::class:
                return new FileTaskRepository($this->config['storage']['file']);
            
            case MySqlTaskRepository::class:
                return new MySqlTaskRepository($this->get(PDO::class));
            
            case InMemoryTaskRepository::class:
                return new InMemoryTaskRepository();
            
            case TaskRepositoryInterface::class:
                $repositoryType = $_SESSION['repository_mode'] ?? $this->config['repository'] ?? 'mysql';
                return match ($repositoryType) {
                    'mysql' => $this->get(MySqlTaskRepository::class),
                    'file' => $this->get(FileTaskRepository::class),
                    default => $this->get(MySqlTaskRepository::class)
                };
            
            case \App\Controller\TaskController::class:
                $repository = $this->get(TaskRepositoryInterface::class);
                return new \App\Controller\TaskController($repository);
            
            default:
                throw new Exception("Неизвестный идентификатор: $id");
        }
    }
}