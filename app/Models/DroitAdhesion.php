<?php

namespace App\Models;

use App\Models\Mutualiste;
use App\Models\Administrateur;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DroitAdhesion extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function mutualiste(): BelongsTo
    {
        return $this->belongsTo(Mutualiste::class);
    }

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class);
    }
}
