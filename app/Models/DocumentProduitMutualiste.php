<?php

namespace App\Models;

use App\Models\TypeDocument;
use App\Models\ProduitProjet;
use App\Models\DemandeProduit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentProduitMutualiste extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class);
    }

    public function demandeProduit(): BelongsTo
    {
        return $this->belongsTo(DemandeProduit::class);
    }

    public function produitProjet(): BelongsTo
    {
        return $this->belongsTo(ProduitProjet::class);
    }

    public function typeDocument(): BelongsTo
    {
        return $this->belongsTo(TypeDocument::class);
    }
}
