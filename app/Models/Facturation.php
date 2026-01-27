<?php

namespace App\Models;

use App\Models\Periode;
use App\Models\Redevance;
use App\Models\Mutualiste;
use App\Models\ProduitProjet;
use App\Models\Administrateur;
use App\Models\ProjetMutualiste;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facturation extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class);
    }

    public function mutualiste(): BelongsTo
    {
        return $this->belongsTo(Mutualiste::class);
    }

    public function projetMutualiste(): BelongsTo
    {
        return $this->belongsTo(ProjetMutualiste::class);
    }

    public function produitProjet(): BelongsTo
    {
        return $this->belongsTo(ProduitProjet::class);
    }

    public function redevance(): BelongsTo
    {
        return $this->belongsTo(Redevance::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }


    // public function documentPaiements(): HasMany
    // {
    //     return $this->hasMany(DocumentPaiement::class);
    // }
}
