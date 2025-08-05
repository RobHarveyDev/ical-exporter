<?php

namespace App\Policies;

use App\Models\Feed;
use App\Models\User;

class FeedPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Feed $feed): bool
    {
        return $feed->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Feed $feed): bool
    {
        return $feed->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Feed $feed): bool
    {
        return $feed->user_id === $user->id;
    }
}
