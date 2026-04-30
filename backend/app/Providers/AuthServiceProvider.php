<?php

namespace App\Providers;

use App\Models\Candidature;
use App\Models\Election;
use App\Policies\CandidaturePolicy;
use App\Policies\ElectionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Candidature::class => CandidaturePolicy::class,
        Election::class => ElectionPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
