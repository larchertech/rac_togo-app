<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cdej extends Model
{
    use HasFactory;

    protected $table = 'cdej';

    protected $fillable = ['nom', 'ville', 'region', 'cluster_id', 'coordinateur_id'];

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function alumni()
    {
        return $this->hasMany(Alumni::class);
    }

    public function coordinateur()
    {
        return $this->belongsTo(User::class, 'coordinateur_id');
    }
}
