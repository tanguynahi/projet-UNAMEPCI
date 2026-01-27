<?php

namespace App\Models;

use App\Models\Mutualiste;
use App\Models\ProduitProjet;
use App\Models\Administrateur;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentProduitMutualiste;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemandeProduit extends Model
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

    public function produitProjet(): BelongsTo
    {
        return $this->belongsTo(ProduitProjet::class);
    }

    public function documentProduitMutualistes(): HasMany
    {
        return $this->hasMany(DocumentProduitMutualiste::class);
    }


}
