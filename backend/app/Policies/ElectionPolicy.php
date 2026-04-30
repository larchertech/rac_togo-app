<?php

namespace App\Policies;

use App\Models\Election;
use App\Models\User;

class ElectionPolicy
{
    public function view(User $user, Election $election): bool
    {
        return true;
    }

    public function gerer(User $user): bool
    {
        return $user->can('election.gerer');
    }

    public function proclamer(User $user): bool
    {
        return $user->can('election.proclamer');
    }

    public function listeElectorale(User $user): bool
    {
        return $user->can('liste.electorale.voir');
    }
}
