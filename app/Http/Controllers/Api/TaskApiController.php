<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Services\TaskService;
use App\Services\TaskDistributor;
use App\Http\Controllers\Controller;

class TaskApiController extends Controller
{
    public function distributeTasks(TaskService $taskService, TaskDistributor $taskDistributor)
    {
        $taskService->fetchTasks();

        $tasks = Task::all();

        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found'], 404);
        }

        $developers = $taskDistributor->distributeTasks($tasks->toArray());
        $weeks = $taskDistributor->calculateWeeks($developers);

        return response()->json([
            'tasks' => $tasks,
            'developers' => $developers,
            'weeks' => $weeks,
        ]);
    }
}
