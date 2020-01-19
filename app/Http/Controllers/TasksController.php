<?php

namespace App\Http\Controllers;

use App\Board;
use App\Task;
use App\User;

use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a Tasking of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Board $board)
    {
        $this->authorize('owns_board', $board);

        $attributes = $this->validateTask($request);

        // Count number of tasks
        $taskCount = $board->tasks->count();

        $attributes['board_id'] = $board->id;
        $attributes['order'] = $taskCount;

        $task = Task::create($attributes);

        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board)
    {
        
    }

    public function reorder(Request $request, Board $board)
    {
        $this->authorize('owns_board', $board);

        $attributes = $request->validate([
            'start_position' => 'required|numeric',
            'end_position' => 'required|numeric',
        ]);

        $task = $board->tasks->where('order', '=', $attributes['start_position'])->first();

        $direction;
        $selectTasksToUpdateOrder = [];

        if ((int)$attributes['start_position'] < (int)$attributes['end_position']) {
            $direction = -1; // descending
            $selectTasksToUpdateOrder[0] = (int)$attributes['start_position'] +1;
            $selectTasksToUpdateOrder[1] = (int)$attributes['end_position'];
        } else {
            $direction = 1; // ascending
            $selectTasksToUpdateOrder[0] = (int)$attributes['end_position'];
            $selectTasksToUpdateOrder[1] = (int)$attributes['start_position'] -1;
        }

        $tasksToUpdateOrder = Task::where('board_id', '=', $board->id)
            ->whereBetween('order', $selectTasksToUpdateOrder)
            ->orderBy('order')
            ->get();

        $this->reorderTasks($tasksToUpdateOrder, $direction);

        $task->update([ 'order' => (int)$attributes['end_position'] ]);

        return response(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $this->authorize('owns_task', $task);
        
        $task->delete();
    }

    private function validateTask($request)
    {
        return $request->validate([
            'name' => 'required|min:3|max:255',
        ]);
    }

    private function reorderTasks($tasks, $change)
    {
        foreach($tasks as $task) {
            $task->update([
                'order' => $task->order + $change
            ]);
        }
    }
    
}
