<?php

namespace App\Models;

use App\Models\Projet;
use App\Models\Mutualiste;
use App\Models\Facturation;
use App\Models\TypePaiement;
use App\Models\DemandeProjet;
use App\Models\ProduitProjet;
use App\Models\Administrateur;
use App\Models\DemandeProduit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjetMutualiste extends Model
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

    // public function projet(): BelongsTo
    // {
    //     return $this->belongsTo(Projet::class);
    // }

    public function demandeProduit(): BelongsTo
    {
        return $this->belongsTo(DemandeProduit::class);
    }

    public function produitProjet(): BelongsTo
    {
        return $this->belongsTo(ProduitProjet::class);
    }

    public function typePaiment(): BelongsTo
    {
        return $this->belongsTo(TypePaiement::class);
    }

    public function facturations(): HasMany
    {
        return $this->hasMany(Facturation::class);
    }


}
