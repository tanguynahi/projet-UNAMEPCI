<?php

namespace App\Models;

use App\Models\TypeDocument;
use App\Models\ProduitProjet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentProduit extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function produitProjet(): BelongsTo
    {
        return $this->belongsTo(ProduitProjet::class);
    }

    public function typeDocument(): BelongsTo
    {
        return $this->belongsTo(TypeDocument::class);
    }
}
