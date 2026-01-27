<?php

namespace App\Models;

use App\Models\Mutualiste;
use App\Models\TypePaiement;
use App\Models\DocumentPaiement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaiementInitiale extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];


    public function mutualiste(): BelongsTo
    {
        return $this->belongsTo(Mutualiste::class);
    }

    public function typePaiement(): BelongsTo
    {
        return $this->belongsTo(TypePaiement::class);
    }
    public function documentPaiements(): HasMany
    {
        return $this->hasMany(DocumentPaiement::class);
    }

}
