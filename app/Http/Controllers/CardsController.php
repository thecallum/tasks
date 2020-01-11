<?php

namespace App\Http\Controllers;

use App\Card;
use App\Task;
use App\Board;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
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
    public function store(Request $request, Task $task)
    {
        $this->authorize('owns_task', $task);

        $attributes = $this->validateCard($request);

        $attributes['task_id'] = $task->id;
        // Need to auto increment this...
        $attributes['order'] = 1;
        $attributes['board_id'] = $task->board_id;

        $card = Card::create($attributes);

        return $card;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {   
        $this->authorize('owns_card', $card);
        $attributes = $this->validateCard($request);
        $card->update($attributes);
        return $card;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        $this->authorize('owns_card', $card);
        $card->delete();
        return response(null, 200);
    }

        private function validateCard($request)
    {
        return $request->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'nullable|min:3|max:255',
        ]);
    }
}
