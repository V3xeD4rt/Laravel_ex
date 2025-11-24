<?php

namespace App\Model;

class Task
{
    private int $id;
    private string $title;
    private bool $completed = false;

    public function __construct(string $title, bool $completed = false, int $id = 0)
    {
        $this->title = $title;
        $this->completed = $completed;
        $this->id = $id;
    }
    
    public function getId(): int {
        return $this->id;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function complete(): void {
        $this->completed = true;
    }
    
    public function setCompleted(bool $completed): void {
        $this->completed = $completed;
    }
}