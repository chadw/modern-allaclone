<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        return view('tasks.index');
    }

    public function show(Task $task)
    {
        return view('tasks.show');
    }
}
