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

        $attributes['board_id'] = $board->id;
        // Need to auto increment this...
        $attributes['order'] = 1;

        $task = Task::create($attributes);

        return back();
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
    public function update(Request $request, Task $task)
    {
        //
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

}
