<?php

namespace App\Services;

class TaskDistributor
{
    protected $developerService;

    public function __construct(DeveloperService $developerService)
    {
        $this->developerService = $developerService;
    }

    public function distributeTasks($tasks)
    {
        $developers = $this->developerService->fetchDevelopers();
        $workload = [
            'Developer 1' => 1,
            'Developer 2' => 2,
            'Developer 3' => 3,
            'Developer 4' => 4,
            'Developer 5' => 5,
        ];

        foreach ($developers as &$developer) {
            $developer['tasks'] = [];
            $developer['hoursWorked'] = 0;
            $developer['capacity'] = 45;
        }

        usort($tasks, function ($task1, $task2) {
            return ($task2['time'] * $task2['level']) - ($task1['time'] * $task1['level']);
        });

        foreach ($tasks as $task) {
            foreach ($developers as &$developer) {
                if ($task['level'] <= $workload[$developer['name']] &&
                    ($developer['hoursWorked'] + $task['time']) <= $developer['capacity']) {
                    $developer['tasks'][] = $task;
                    $developer['hoursWorked'] += $task['time'];
                    break;
                }
            }
        }

        return $developers;
    }

    public function calculateWeeks($developers)
    {
        $totalHours = array_sum(array_column($developers, 'hoursWorked'));
        return ceil($totalHours / 45);
    }
}


