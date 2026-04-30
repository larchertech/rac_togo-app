<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'phone_whatsapp',
        'email',
        'role',
        'statut',
        'otp_code',
        'otp_expires_at',
        'otp_tentatives',
        'canal_prefere',
        'derniere_connexion',
    ];

    protected $hidden = [
        'otp_code',
        'remember_token',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'derniere_connexion' => 'datetime',
    ];

    public function alumni()
    {
        return $this->hasMany(Alumni::class);
    }

    public function alumniProfil()
    {
        return $this->hasOne(Alumni::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'destinataire_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function emargements()
    {
        return $this->hasMany(Emargement::class, 'electeur_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCena(): bool
    {
        return in_array($this->role, ['cena', 'admin']);
    }

    public function isBen(): bool
    {
        return in_array($this->role, ['ben', 'cena', 'admin']);
    }

    public function canVote(): bool
    {
        return in_array($this->role, ['alumni', 'bla', 'bca', 'ben']) && $this->statut === 'actif';
    }
}
