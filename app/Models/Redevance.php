<?php

namespace App\Models;

use App\Models\Projet;
use App\Models\Periode;
use App\Models\Administrateur;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Redevance extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }
}
