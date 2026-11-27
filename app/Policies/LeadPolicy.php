<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view leads');
    }

    public function view(User $user, Lead $lead): bool
    {
        if ($user->can('view leads')) {
            return true;
        }

        return $lead->assigned_to_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('create leads');
    }

    public function update(User $user, Lead $lead): bool
    {
        if ($user->can('edit leads')) {
            return true;
        }

        return $lead->assigned_to_id === $user->id;
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $user->can('delete leads');
    }

    public function restore(User $user): bool
    {
        return $user->can('delete leads');
    }

    public function forceDelete(User $user): bool
    {
        return $user->can('delete leads');
    }
}
