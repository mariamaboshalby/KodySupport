<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function votePost(Request $request, Post $post)
    {
        $value = (int) $request->validate(['value' => 'required|in:-1,1'])['value'];
        return $this->handleVote($post, $value);
    }

    public function voteComment(Request $request, Comment $comment)
    {
        $value = (int) $request->validate(['value' => 'required|in:-1,1'])['value'];
        return $this->handleVote($comment, $value);
    }

    private function handleVote($votable, int $value): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        $existing = Vote::where('user_id', $user->id)
            ->where('votable_type', get_class($votable))
            ->where('votable_id', $votable->id)
            ->first();

        if ($existing) {
            if ($existing->value === $value) {
                // Un-vote
                $existing->delete();
                $votable->decrement('votes_count', $value > 0 ? 1 : -1);
                $newTotal = $votable->fresh()->votes_count;
                return response()->json(['votes' => $newTotal, 'userVote' => 0]);
            }
            // Change vote
            $diff = $value - $existing->value;
            $existing->update(['value' => $value]);
            $votable->increment('votes_count', $diff);
        } else {
            Vote::create([
                'user_id'     => $user->id,
                'votable_type'=> get_class($votable),
                'votable_id'  => $votable->id,
                'value'       => $value,
            ]);
            $votable->increment('votes_count', $value);
        }

        $newTotal = $votable->fresh()->votes_count;
        return response()->json(['votes' => $newTotal, 'userVote' => $value]);
    }
}
