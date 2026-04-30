<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'region', 'facilitateur_id'];

    public function cdejs()
    {
        return $this->hasMany(Cdej::class);
    }

    public function facilitateur()
    {
        return $this->belongsTo(User::class, 'facilitateur_id');
    }

    public function alumni()
    {
        return $this->hasManyThrough(Alumni::class, Cdej::class);
    }
}
