<?php

namespace App\Policies;

use App\User;
use App\Demand;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function pass(User $user, Post $post){
        return $user->id == $post->user_id;
    }

    public function updatePost(User $user, Demand $post){
        return $user->id == $post->user_id;
    }
}
