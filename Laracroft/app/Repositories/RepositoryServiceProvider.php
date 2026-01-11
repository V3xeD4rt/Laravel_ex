<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\TaskRepositoryInterface::class,
            function ($app) {
                $mode = session('repository_mode', config('app.repository_mode', 'mysql'));
                
                return match ($mode) {
                    'file' => new \App\Repositories\FileTaskRepository(),
                    'memory' => new \App\Repositories\InMemoryTaskRepository(),
                    default => new \App\Repositories\MySqlTaskRepository(),
                };
            }
        );
    }

    public function boot(): void
    {
        //
    }
}