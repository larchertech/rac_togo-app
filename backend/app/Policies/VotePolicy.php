<?php

namespace App\Policies;

use App\Models\Election;
use App\Models\User;
use App\Models\Candidature;

class VotePolicy
{
    public function cast(User $user, Election $election): bool
    {
        // Ne peut pas voter si membre de la commission organisatrice
        $commission = $election->commission;
        if ($commission && in_array($user->id, $commission->membres ?? [])) {
            return false;
        }

        // Ne peut pas voter si candidat dans cette élection
        $estCandidat = Candidature::where('election_id', $election->id)
            ->whereHas('alumni', fn($q) => $q->where('user_id', $user->id))
            ->where('statut', 'valide')
            ->exists();

        return !$estCandidat && $user->can('vote.cast');
    }
}
