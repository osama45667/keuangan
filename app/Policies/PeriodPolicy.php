<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AccountingPeriod;

class PeriodPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage periods');
    }

    public function view(User $user, AccountingPeriod $period): bool
    {
        return $user->can('manage periods');
    }

    public function create(User $user): bool
    {
        return $user->can('manage periods');
    }

    public function update(User $user, AccountingPeriod $period): bool
    {
        return $user->can('manage periods');
    }

    public function delete(User $user, AccountingPeriod $period): bool
    {
        return $user->can('manage periods');
    }
}
