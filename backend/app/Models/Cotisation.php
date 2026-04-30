<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_id',
        'annee',
        'montant',
        'statut',
        'canal_paiement',
        'recu_numero',
        'reference_externe',
        'paid_at',
        'exemption',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'exemption' => 'boolean',
        'montant' => 'decimal:2',
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }
}
