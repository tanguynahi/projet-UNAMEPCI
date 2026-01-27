<?php

namespace App\Models;

use App\Models\Projet;
use App\Models\Demande;
use App\Models\ImageProjet;
use App\Models\TypePaiement;
use App\Models\Administrateur;
use App\Models\DocumentProduit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProduitProjet extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];


    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class);
    }

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class);
    }

    public function typePaiement(): BelongsTo
    {
        return $this->belongsTo(TypePaiement::class);
    }

    public function imageProjets(): HasMany
    {
        return $this->hasMany(ImageProjet::class);
    }

    public function documentProduits(): HasMany
    {
        return $this->hasMany(DocumentProduit::class);
    }

    public function documentProduitMutualistes(): HasMany
    {
        return $this->hasMany(DocumentProduitMutualiste::class);
    }
}
