<?php
namespace App\Contracts;

interface TaskProviderInterface
{
    public function fetchTasks(): array;
}
