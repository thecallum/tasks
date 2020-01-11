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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Task $task)
    {
        $this->authorize('owns_task', $task);

        $attributes = $this->validateCard($request);

        // Count Number of cards
        $cardCount = $task->cards->count();

        $attributes['task_id'] = $task->id;
        $attributes['order'] = $cardCount + 1;
        $attributes['board_id'] = $task->board_id;

        $card = Card::create($attributes);

        return $card;
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

        $cardPosition = $card->order;
        $card->delete();
    
        /* Update order by -1 to cards after the deleted card */

        $cardsToUpdate = $card->task->cards->where('order', '>', $cardPosition);

        foreach($cardsToUpdate as $card) {
            $card->update([ 'order' => $card->order - 1 ]);
        }

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
