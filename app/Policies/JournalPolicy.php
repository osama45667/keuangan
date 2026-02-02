<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Journal;

class JournalPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage journals');
    }

    public function view(User $user, Journal $journal): bool
    {
        return $user->can('manage journals');
    }

    public function create(User $user): bool
    {
        return $user->can('manage journals');
    }

    public function update(User $user, Journal $journal): bool
    {
        return $user->can('manage journals');
    }

    public function delete(User $user, Journal $journal): bool
    {
        return $user->can('manage journals');
    }
}
