<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Account;

class AccountPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage coa');
    }

    public function view(User $user, Account $account): bool
    {
        return $user->can('manage coa');
    }

    public function create(User $user): bool
    {
        return $user->can('manage coa');
    }

    public function update(User $user, Account $account): bool
    {
        return $user->can('manage coa');
    }

    public function delete(User $user, Account $account): bool
    {
        return $user->can('manage coa');
    }
}
