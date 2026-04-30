<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'user_id',
        'cdej_id',
        'nom',
        'prenom',
        'date_naissance',
        'niveau_diplome',
        'documents',
        'statut_compte',
        'numero_membre',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'documents' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($alumni) {
            if (empty($alumni->numero_membre) && $alumni->statut_compte === 'valide') {
                $alumni->numero_membre = self::genererNumeroMembre();
            }
        });

        static::updating(function ($alumni) {
            if ($alumni->isDirty('statut_compte') && $alumni->statut_compte === 'valide' && empty($alumni->numero_membre)) {
                $alumni->numero_membre = self::genererNumeroMembre();
            }
        });
    }

    public static function genererNumeroMembre(): string
    {
        $annee = date('Y');
        $count = DB::table('alumni')->whereNotNull('numero_membre')->count() + 1;
        return sprintf('RAC-%s-%05d', $annee, $count);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cdej()
    {
        return $this->belongsTo(Cdej::class);
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

    public function elections()
    {
        return $this->belongsToMany(Election::class, 'emargement', 'electeur_id', 'election_id')
            ->withTimestamps();
    }

    public function estEligibleVote(): bool
    {
        return $this->statut_compte === 'valide' && $this->cotisationAJour();
    }

    public function cotisationAJour(): bool
    {
        $anneeEnCours = (int) date('Y');

        $cotisationPayee = $this->cotisations()
            ->where('annee', $anneeEnCours)
            ->where('statut', 'paye')
            ->exists();

        $exemption = $this->cotisations()
            ->where('annee', $anneeEnCours)
            ->where('exemption', true)
            ->exists();

        return $cotisationPayee || $exemption;
    }

    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
