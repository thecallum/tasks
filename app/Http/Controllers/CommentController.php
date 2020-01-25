<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Card;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Card $card)
    {
        $this->authorize('owns_card', $card);

        $attributes = $request->validate([
            "comment" => "required|min:3|max:4294967295"
        ]);

        $newComment = Comment::create([
            "comment" => $attributes["comment"],
            "user_id" => auth()->user()->id,
            "card_id" => $card->id
        ]);

        return $newComment;
    }

    public function destroy(Comment $comment)
    {
        $this->authorize("owns_comment", $comment);
        $comment->delete();
        return response(null, 200);
    }
}
