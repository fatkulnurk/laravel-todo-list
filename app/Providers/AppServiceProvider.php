<?php

namespace App\Providers;

use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Tasks\TaskRepository;
use App\Services\Contracts\TaskServiceInterface;
use App\Services\Tasks\TaskService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // task repository
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        // task svc
        $this->app->bind(TaskServiceInterface::class, TaskService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
