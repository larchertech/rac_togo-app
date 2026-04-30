<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'destinataire_id',
        'canal',
        'type',
        'message',
        'statut',
        'tentatives',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function destinataire()
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }
}
