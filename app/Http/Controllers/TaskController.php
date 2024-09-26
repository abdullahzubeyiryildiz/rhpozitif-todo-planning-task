<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use App\Services\TaskDistributor;

class TaskController extends Controller
{
    public function index(){
        return view('tasks.result');
    }
}
