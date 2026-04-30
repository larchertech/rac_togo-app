<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'niveau',
        'statut',
        'commission_id',
        'ouverture_candidatures',
        'cloture_candidatures',
        'date_vote',
        'heure_ouverture_vote',
        'heure_cloture_vote',
        'mode_scrutin',
        'postes',
        'config',
        'proclame_at',
    ];

    protected $casts = [
        'ouverture_candidatures' => 'datetime',
        'cloture_candidatures' => 'datetime',
        'date_vote' => 'datetime',
        'heure_ouverture_vote' => 'datetime',
        'heure_cloture_vote' => 'datetime',
        'postes' => 'array',
        'config' => 'array',
        'proclame_at' => 'datetime',
    ];

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

    public function emargements()
    {
        return $this->hasMany(Emargement::class);
    }

    public function resultats()
    {
        return $this->hasMany(Resultat::class);
    }

    public function scopeActives($query)
    {
        return $query->whereIn('statut', ['candidatures', 'campagne', 'vote', 'depouillement']);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeParNiveau($query, $niveau)
    {
        return $query->where('niveau', $niveau);
    }

    public function estOuverte(): bool
    {
        $now = now();
        $ouverture = $this->date_vote->copy()->setTimeFrom($this->heure_ouverture_vote);
        $cloture = $this->date_vote->copy()->setTimeFrom($this->heure_cloture_vote);

        return $this->statut === 'vote' && $now >= $ouverture && $now <= $cloture;
    }

    public function tauxParticipation(): float
    {
        $inscrits = $this->nbElecteursInscrits();
        $votants = $this->emargements()->count();

        return $inscrits > 0 ? round(($votants / $inscrits) * 100, 2) : 0;
    }

    public function nbElecteursInscrits(): int
    {
        return match ($this->type) {
            'bla' => Alumni::where('cdej_id', $this->niveau)
                ->where('statut_compte', 'valide')
                ->count(),
            'bca' => Alumni::whereHas('cdej', fn($q) => $q->where('cluster_id', $this->niveau))
                ->where('statut_compte', 'valide')
                ->count(),
            'be' => Alumni::where('statut_compte', 'valide')->count(),
            default => 0,
        };
    }
}
