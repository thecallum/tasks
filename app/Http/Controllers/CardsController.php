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
        $attributes['order'] = $cardCount;
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
        $task_id = $card->task_id;
        $card->delete();
    
        $cardsToUpdate = Card::where('task_id', '=', $task_id)
            ->where('order', '>', $cardPosition)
            ->orderBy('order')->get();

        $this->reorderCards($cardsToUpdate, -1);

        return response(null, 200);
    }

    public function updateOrder(Request $request, Card $card)
    {   
        
        /*
            Validation
            ===============
            1. User Owns card's task
            2. User owns new task (if moving card to another list)

        */

        $this->authorize('owns_task', $card->task);

        $attributes = $request->validate([
            'start_list' => 'required|integer',
            'end_list' => 'required|integer',
            'new_position' => 'required|integer',            
        ]);

        if ((int)$attributes['start_list'] === (int)$attributes['end_list']) {
            // Same List
            if ($card->order === $attributes['new_position']) 
                return; /* card hasn't moved */

            $task = $card->task;
            $selectedCard = $card;
            $direction; /* Direction the card moved */
            
            /* Start and end position of cards that need their order updated */
            $startIndex;
            $endIndex;

            if ((int)$card->order < (int)$attributes['new_position']) {
                $direction = 'descending';
                $startIndex = (int)$card->order +1;
                $endIndex = (int)$attributes['new_position'];
            } else {
                $direction = 'ascending';
                $endIndex = (int)$card->order -1;
                $startIndex = (int)$attributes['new_position'];
            }

            $cardsToMove = $task->cards->whereBetween('order', [$startIndex, $endIndex]);

            $this->reorderCards($cardsToMove, ($direction === 'descending' ? -1 : 1));

            $selectedCard->update([
                'order' => (int)$attributes['new_position']
            ]);

        } else {
            // Multiple Lists
            $this->authorize('owns_task', Task::find($attributes['end_list']));

            $selectedCard = $card;
            $task = $selectedCard->task;
            $board = $task->board;

            $cardsInStartList = Card::where('task_id', '=', $selectedCard->task_id)
                ->where('order', '>', $selectedCard->order)
                ->orderBy('order')->get();

            $cardsInEndList = Card::where('task_id', '=', $attributes['end_list'])
                ->where('order', '>=', $attributes['new_position'])
                ->orderBy('order')->get();
                
            $this->reorderCards($cardsInStartList, -1);
            $this->reorderCards($cardsInEndList, 1);

            $selectedCard->update([
                'task_id' => $attributes['end_list'],
                'order' => $attributes['new_position']
            ]);
        }
    }

    private function validateCard($request)
    {
        return $request->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'nullable|min:3|max:255',
        ]);
    }

    private function reorderCards($cards, $change)
    {
        foreach($cards as $card) {
            $card->update([
                'order' => $card->order + $change
            ]);
        }
    }
}
