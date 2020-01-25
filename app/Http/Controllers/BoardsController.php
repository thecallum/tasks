<?php

namespace App\Http\Controllers;

use App\Board;
use App\Comment;
use Illuminate\Http\Request;

class BoardsController extends Controller
{
    public function index()
    {
        $boards = auth()->user()->boards;

        return view('boards.index', compact('boards'));
    }

    public function create()
    {
        return view('boards.create');
    }

    public function store(Request $request)
    {
        $attributes = $this->validateBoard($request);
        $attributes['user_id'] = auth()->id();

        $board = Board::create($attributes);

        return redirect('/boards/' . $board->id);
    }

    public function show(Board $board)
    {
        $this->authorize('update', $board);

        $tasks = $board->tasks;
        $cards = $board->cards;
        $comments = $board->comments
            ->join('users', 'user_id', '=', 'users.id')
            ->select('comments.*', 'users.name')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('boards.view', compact(['board', 'tasks', 'cards', 'comments']));
    }

    public function edit(Board $board)
    {
        $this->authorize('update', $board);

        return view('boards.edit', compact('board'));
    }

    public function update(Request $request, Board $board)
    {
        $this->authorize('update', $board);

        $attributes = $this->validateBoard($request);

        $board->update($attributes);

        return redirect('/boards/' . $board->id);
    }

    public function destroy(Board $board)
    {
        $this->authorize('update', $board);

        $board->delete();

        return redirect('/boards');
    }

    private function validateBoard($request)
    {
        return $request->validate([
            'name' => 'required|min:3|max:255',
        ]);
    }
}
