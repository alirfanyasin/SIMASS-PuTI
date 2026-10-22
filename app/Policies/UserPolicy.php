<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function before(User $actor, string $ability): ?bool
    {
        if (! $actor->is_active) {
            return false;
        }

        return null;
    }

    public function manage(User $actor): bool
    {
        return $actor->hasRole('super-admin') || $actor->can('manage-users');
    }

    public function deactivate(User $actor, User $target): bool
    {
        if ($actor->id === $target->id) {
            return false; // No self-deactivation
        }

        if ($target->hasRole('super-admin') && $this->isLastActiveSuperAdmin($target)) {
            return false; // Last super-admin standing
        }

        return $this->manage($actor);
    }

    public function delete(User $actor, User $target): bool
    {
        if ($actor->id === $target->id) {
            return false; // No self-deletion
        }

        if ($target->hasRole('super-admin') && $this->isLastActiveSuperAdmin($target)) {
            return false; // Last super-admin standing
        }

        return $this->manage($actor);
    }

    public function demote(User $actor, User $target): bool
    {
        if ($actor->id === $target->id && $actor->hasRole('super-admin')) {
            return false; // No self-demotion
        }

        if ($target->hasRole('super-admin') && $this->isLastActiveSuperAdmin($target)) {
            return false; // Last super-admin standing
        }

        return $this->manage($actor);
    }

    private function isLastActiveSuperAdmin(User $target): bool
    {
        return User::role('super-admin')
            ->where('is_active', true)
            ->where('id', '!=', $target->id)
            ->count() === 0;
    }
}
