<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function create(User $user): bool
    {
        return true; // any authenticated user
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isModerator();
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isModerator();
    }
}
