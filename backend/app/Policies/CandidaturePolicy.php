<?php

namespace App\Policies;

use App\Models\Candidature;
use App\Models\User;

class CandidaturePolicy
{
    public function view(User $user, Candidature $candidature): bool
    {
        return true;
    }

    public function valider(User $user): bool
    {
        return $user->can('candidature.valider');
    }

    public function rejeter(User $user): bool
    {
        return $user->can('candidature.rejeter');
    }

    public function deposer(User $user): bool
    {
        return $user->can('candidature.soumettre');
    }
}
