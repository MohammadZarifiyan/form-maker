<?php

namespace App\Policies;

use App\Models\Query;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QueryPolicy
{
    use HandlesAuthorization;

    public function __construct(public $runnable_query)
    {
        //
    }

    public function view(User $user, Query $query)
    {
        $query->load('connection');

        return $user->id === $query->connection->user_id;
    }

    public function update(User $user, Query $query)
    {
        $query->load('connection');

        return $user->id === $query->connection->user_id;
    }

    public function delete(User $user, Query $query)
    {
        $query->load('connection');

        return $user->id === $query->connection->user_id;
    }

    public function run(User $user)
    {
        if (!$this->runnable_query) {
            return false;
        }

        $this->runnable_query->load('connection');

        return $user->id === $this->runnable_query->connection->user_id;
    }
}
