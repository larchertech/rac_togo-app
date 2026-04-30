<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Resultat extends Model
{
    use HasFactory;

    protected $table = 'resultats';

    protected $fillable = [
        'election_id',
        'candidature_id',
        'nb_voix',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function candidature()
    {
        return $this->belongsTo(Candidature::class);
    }

    public function incrementer(): void
    {
        DB::transaction(function () {
            $this->lockForUpdate();
            $this->increment('nb_voix');
        });
    }
}
