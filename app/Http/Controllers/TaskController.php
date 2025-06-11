<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Filters\TaskFilter;
use Illuminate\Http\Request;
use App\Models\AlternateCurrency;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = (new TaskFilter($request))
            ->apply(Task::query())
            ->orderBy('title', 'asc')
            ->paginate(50)
            ->withQueryString();

        // get alt currency since tasks could use it
        $altCurrency = AlternateCurrency::with('item:id,Name,icon')->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'altCurrency' => $altCurrency,
            'metaTitle' => config('app.name') . ' - Task Search',
        ]);
    }

    public function show(Task $task)
    {
        $activities = Cache::remember("task_with_activities_{$task->id}", now()->addMonth(), function () use ($task) {
            $task = Task::with('taskActivities')
                ->where('id', $task->id)
                ->where('enabled', 1)
                ->firstOrFail();

            $task->final_description = $task->fixed_description;

            foreach ($task->taskActivities as $activity) {
                $activity->cached_npcs = $activity->npcs;
                $activity->cached_zones = $activity->zones;
                $activity->cached_items = $activity->items;
            }

            return $task;
        });

        // get alt currency since tasks could use it
        $altCurrency = AlternateCurrency::with('item:id,Name,icon')->get();

        return view('tasks.show', [
            'task' => $activities,
            'altCurrency' => $altCurrency,
            'metaTitle' => config('app.name') . ' - Task: ' . $task->title,
        ]);
    }
}
