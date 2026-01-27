<?php

namespace App\Models;

use App\Models\Direction;
use App\Models\ImageProjet;
use App\Models\ProduitProjet;
use App\Models\Administrateur;
use App\Models\DocumentProduit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Projet extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class);
    }

    public function direction(): BelongsTo
    {
        return $this->belongsTo(Direction::class);
    }

    public function imagesProjet(): HasMany
    {
        return $this->hasMany(ImageProjet::class);
    }

    public function produitsProjet(): HasMany
    {
        return $this->hasMany(ProduitProjet::class);
    }

}
