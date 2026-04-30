<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'alumni_id',
        'poste',
        'statut',
        'documents',
        'lettre_motivation',
        'motif_rejet',
        'valide_par',
        'valide_at',
        'numero_dossier',
    ];

    protected $casts = [
        'documents' => 'array',
        'valide_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($candidature) {
            if (empty($candidature->numero_dossier)) {
                $candidature->numero_dossier = self::genererNumeroDossier();
            }
        });
    }

    public static function genererNumeroDossier(): string
    {
        $annee = date('Y');
        $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        return sprintf('CDC-%s-%s', $annee, $random);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    public function resultat()
    {
        return $this->hasOne(Resultat::class);
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function getNbVoixAttribute(): int
    {
        return $this->resultat ? $this->resultat->nb_voix : 0;
    }
}
