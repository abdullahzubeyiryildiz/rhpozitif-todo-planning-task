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
     * Görevleri iş yüküne, kapasiteye ve görev düzeyine göre geliştiriciler arasında dağıtır.
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
     * Geliştiricileri kapasite ve iş yükleriyle başlatır.
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
            $developer['level'] = $developer['level'] ?? 5;
        }
        return $developers;
    }


    /**
     * Mevcut kapasiteye ve maksimum görev seviyesine göre uygun bir geliştiriciye görev atar.
     *
     * @param array $task
     * @param array &$developers
     * @return void
     */
    protected function assignTaskToDeveloper(array $task, array &$developers): void
    {
        usort($developers, fn($a, $b) => $a['hoursWorked'] <=> $b['hoursWorked']);

        foreach ($developers as &$developer) {
            $developerHourlyWork = $developer['capacity'] / $developer['level'];

            if ($task['level'] <= $developer['level'] && $task['time'] <= $developerHourlyWork) {
                $developer['tasks'][] = $task;
                $developer['hoursWorked'] += $task['time'];
                break;
            }
        }
    }



    /**
     * Geliştirici kapasitesi ve görev yüküne bağlı olarak gereken hafta sayısını hesaplar.
     *
     * @param array $developers
     * @return int
     */
    public function calculateWeeks(array $developers): array
    {
        $totalWeeks = 0;
        $assignedTasksCount = [];

        foreach ($developers as $developer) {
            $totalDeveloperHours = $developer['hoursWorked'];
            $developerWeeks = (int) ceil($totalDeveloperHours / $developer['capacity']);

            $assignedTasksCount[$developer['name']] = count($developer['tasks']);

            if ($developerWeeks > $totalWeeks) {
                $totalWeeks = $developerWeeks;
            }
        }

        return [
            'weeks' => $totalWeeks,
            'assignedTasksCount' => $assignedTasksCount
        ];
    }

}
