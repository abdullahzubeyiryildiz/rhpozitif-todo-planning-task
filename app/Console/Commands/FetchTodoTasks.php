<?php

namespace App\Console\Commands;

use App\Services\TaskService;
use Illuminate\Console\Command;
class FetchTodoTasks extends Command
{
    protected $signature = 'fetch:todos';
    protected $description = 'Task List Service';

    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        parent::__construct();
        $this->taskService = $taskService;
    }

    public function handle()
    {
        $this->taskService->fetchTasks();
        $this->info('Tasklar Kaydedildi.');
    }
}
