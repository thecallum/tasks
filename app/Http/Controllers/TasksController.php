<?php

namespace App\Http\Controllers;

use App\Board;
use App\Task;
use App\User;

use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function store(Request $request, Board $board)
    {
        $this->authorize('owns_board', $board);

        $attributes = $this->validateTask($request);

        $taskCount = $board->tasks->count();

        $attributes['board_id'] = $board->id;
        $attributes['order'] = $taskCount;

        $task = Task::create($attributes);

        return $task;
    }

    public function reorder(Request $request, Board $board)
    {
        $this->authorize('owns_board', $board);

        $attributes = $request->validate([
            'start_position' => 'required|numeric',
            'end_position' => 'required|numeric',
        ]);

        $task = $board->findCardAtPosition($attributes['start_position']);

        $this->updateTaskOrderBetweenRange($board, $attributes['start_position'], $attributes['end_position']);

        $task->update([ 'order' => $attributes['end_position'] ]);

        return response(null, 200);
    }

    private function updateTaskOrderBetweenRange($board, $startPosition, $endPosition)
    {
        $direction;
        $selectTasksToUpdateOrder = [];

        if ((int)$startPosition < (int)$endPosition) {
            $direction = -1; // descending
            $selectTasksToUpdateOrder[0] = (int)$startPosition +1;
            $selectTasksToUpdateOrder[1] = (int)$endPosition;
        } else {
            $direction = 1; // ascending
            $selectTasksToUpdateOrder[0] = (int)$endPosition;
            $selectTasksToUpdateOrder[1] = (int)$startPosition -1;
        }

        $tasksToUpdateOrder = Task::where('board_id', '=', $board->id)
            ->whereBetween('order', $selectTasksToUpdateOrder)
            ->orderBy('order')
            ->get();

        $this->reorderTasks($tasksToUpdateOrder, $direction);
    }

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
            $task->update([ 'order' => $task->order + $change ]);
        }
    }
    
}
