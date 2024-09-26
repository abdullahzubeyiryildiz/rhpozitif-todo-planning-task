<?php

namespace App\Services;

use App\Models\Task;
use App\Factories\TaskProviderFactory;
use App\Contracts\TaskProviderInterface;
use Illuminate\Support\Facades\DB;

class TaskService
{
    protected $providers;

    public function __construct()
    {
        $this->providers = [];
        $providerTypes = config('task-config');

        foreach ($providerTypes as $type) {
            $this->providers[] = TaskProviderFactory::create($type);
        }
    }

    public function fetchTasks(): array
    {
        $tasks = [];
        foreach ($this->providers as $provider) {
            if ($provider instanceof TaskProviderInterface) {
                $fetchedTasks = $provider->fetchTasks();
                $tasks = array_merge($tasks, $fetchedTasks);
            }
        }

        $this->createTasks($tasks);

        return $tasks;
    }

    protected function createTasks(array $tasks): void
    {
        DB::transaction(function () use ($tasks) {
            foreach ($tasks as $taskData) {
                if (!empty($taskData['title']) && !empty($taskData['time']) && !empty($taskData['level'])) {

                    if (!Task::where('title', $taskData['title'])
                             ->where('time', $taskData['time'])
                             ->where('level', $taskData['level'])
                             ->exists()) {
                        Task::create([
                            'title' => $taskData['title'],
                            'time' => $taskData['time'],
                            'level' => $taskData['level'],
                        ]);
                    }
                }
            }
        });
    }

}
