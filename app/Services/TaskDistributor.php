<?php

namespace App\Services;

class TaskDistributor
{
    protected DeveloperService $developerService;

    public function __construct(DeveloperService $developerService)
    {
        $this->developerService = $developerService;
    }

    /**
     * Distributes tasks among developers based on their workload and capacity.
     *
     * @param array $tasks
     * @return array
     */
    public function distributeTasks(array $tasks): array
    {
        $developers = $this->initializeDevelopers($this->developerService->fetchDevelopers());

        usort($tasks, fn($task1, $task2) => ($task2['time'] * $task2['level']) <=> ($task1['time'] * $task1['level']));

        foreach ($tasks as $task) {
            $this->assignTaskToDeveloper($task, $developers);
        }

        return $developers;
    }

    /**
     * Initializes developers with tasks and workload.
     *
     * @param array $developers
     * @return array
     */
    protected function initializeDevelopers(array $developers): array
    {
        foreach ($developers as &$developer) {
            $developer['tasks'] = [];
            $developer['hoursWorked'] = 0;
            $developer['capacity'] = 45;
        }
        return $developers;
    }

    /**
     * Assigns a task to a developer if possible.
     *
     * @param array $task
     * @param array &$developers
     * @return void
     */
    protected function assignTaskToDeveloper(array $task, array &$developers): void
    {
        foreach ($developers as &$developer) {

            if ($task['level'] <= $developer['capacity'] &&
                ($developer['hoursWorked'] + $task['time']) <= $developer['capacity']) {
                $developer['tasks'][] = $task;
                $developer['hoursWorked'] += $task['time'];
                break;
            }
        }
    }

    /**
     * Calculates the total number of weeks required based on the developers' hours worked.
     *
     * @param array $developers
     * @return int
     */
    public function calculateWeeks(array $developers): int
    {
        $totalHours = array_sum(array_column($developers, 'hoursWorked'));
        return (int) ceil($totalHours / 45);
    }
}
