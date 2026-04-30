<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'niveau',
        'membres',
        'config',
    ];

    protected $casts = [
        'membres' => 'array',
        'config' => 'array',
    ];

    public function elections()
    {
        return $this->hasMany(Election::class);
    }

    public function membresDetails()
    {
        return User::whereIn('id', $this->membres ?? [])->get();
    }
}
